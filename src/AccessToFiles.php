<?php

namespace Scaleplan\AccessToFiles;

use Scaleplan\AccessToFiles\Exceptions\AccessToFilesException;
use Scaleplan\Redis\RedisSingleton;

/**
 * Управление доступом к приватным файлам
 *
 * Class AccessToFiles
 *
 * @package Scaleplan\AccessToFiles
 */
class AccessToFiles
{
    /**
     * Доступные данные для уникальной идентификации клиента
     */
    protected const SERVER_FINGERPRINT_ALLOW_DATA = [
        'HTTP_ACCEPT_LANGUAGE',
        'HTTP_CONNECTION',
        'HTTP_HOST',
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'REMOTE_ADDR',
    ];

    /**
     * По каким заголовкам определять подлинность клиента
     *
     * @var array
     */
    private $actualServerFingerPrint = ['REMOTE_ADDR'];

    /**
     *  Ключ cookie-массива указывающий на идентификатор сессии
     *
     * @const string
     */
    protected const SESSION_KEY = 'qooiz';

    /**
     * Разделитель для значений уникально идентифицирующих пользователя
     *
     * @const string
     */
    protected const STORAGE_KEY_FINGERPRINT_SEPARATOR = ':';

    /**
     * Доступные СУБД для использования
     */
    protected const ALLOW_STORAGE_TYPES = ['redis'];

    /**
     * Объекты класса AccessToFiles
     *
     * @var array
     */
    private static $instances = [];

    /**
     * Данные уникально идентифицирующие клиента
     *
     * @var array
     */
    private $fingerPrintData;

    /**
     * К каким файлам открываем доступ
     *
     * @var array of string
     */
    private $files = [];

    /**
     * Путь к сокету СУБД, хранящей данные открытых для доступа файлов (предположительно к Redis)
     *
     * @var string
     */
    private $storageSocketPath = '/var/run/redis/redis.sock';

    /**
     * Тип СУБД хранящей данные об открытых для доступа файлах
     *
     * @var string
     */
    private $storageType = 'redis';

    /**
     * На сколько будет открывать доступ к файлам
     *
     * @var int
     */
    private $storageTTL = 7200;

    /**
     * Синглтон для класса AccessToFiles
     *
     * @param int $storageTTL - время открытия доступа к файлам
     * @param array|null $actualServerFingerPrint - какую часть доступных данных для идентификации пользователя используем
     * @param string $storageSocketPath - путь к сокету СУБД
     * @param string $storageType - тип СУБД
     *
     * @return AccessToFiles
     *
     * @throws AccessToFilesException
     */
    public static function getInstance(
        int $storageTTL = 0,
        array $actualServerFingerPrint = [],
        string $storageSocketPath = '',
        string $storageType = ''
    ): AccessToFiles
    {
        if (empty(static::$instances[$storageTTL])) {
            static::$instances[$storageTTL]
                = new static($actualServerFingerPrint, $storageSocketPath, $storageType, $storageTTL);
        }

        return static::$instances[$storageTTL];
    }

    /**
     * Конструктор
     *
     * @param array|null $actualServerFingerPrint - какую часть доступных данных для идентификации пользователя используем
     * @param string $storageSocketPath - путь к сокету СУБД
     * @param string $storageType - тип СУБД
     * @param int $storageTTL - время открытия доступа к файлам
     *
     * @throws AccessToFilesException
     */
    protected function __construct(
        array $actualServerFingerPrint = null,
        string $storageSocketPath = '',
        string $storageType = '',
        int $storageTTL = 0
    )
    {
        if (empty($_COOKIE[static::SESSION_KEY])) {
            throw new AccessToFilesException('Не задан ключ для доступа к идентификатору сессии');
        }

        if ($storageSocketPath) {
            $this->storageSocketPath = $storageSocketPath;
        }

        if ($storageType && \in_array($storageType, static::ALLOW_STORAGE_TYPES, true)) {
            $this->storageType = $storageType;
        }

        if ($storageTTL) {
            $this->storageTTL = $storageTTL;
        }

        if ($actualServerFingerPrint === null) {
            $this->actualServerFingerPrint
                = $actualServerFingerPrint ?? ($this->actualServerFingerPrint ?: static::SERVER_FINGERPRINT_ALLOW_DATA);
        }

        $this->fingerPrintData = array_map(function ($item) {
            return $_SERVER[$item] ?? '';
        }, $this->actualServerFingerPrint);

        $this->fingerPrintData[] = $_COOKIE[static::SESSION_KEY];
    }

    /**
     * Установить путь к Unix-сокету подключения к хранилищу метаданных
     *
     * @param string $storageSocketPath
     */
    public function setStorageSocketPath(string $storageSocketPath): void
    {
        $this->storageSocketPath = $storageSocketPath;
    }

    /**
     * Уставновить набор заголовком, учавствующих в однозначной уатентификации клиента
     *
     * @param array $actualServerFingerPrint
     */
    public function setActualServerFingerPrint(array $actualServerFingerPrint): void
    {
        $this->actualServerFingerPrint = $actualServerFingerPrint;
    }

    /**
     * Установить тип хранилица
     *
     * @param string $storageType - тип СУБД
     *
     * @return bool
     */
    public function setStorageType(string $storageType): bool
    {
        if ($storageType && \in_array($storageType, static::ALLOW_STORAGE_TYPES, true)) {
            return (bool) $this->storageType = $storageType;
        }

        return false;
    }

    /**
     * Добавить файлы для открытия доступа
     *
     * @param array $files - массив путей к файлам
     */
    public function addFiles(array $files): void
    {
        $this->files = array_merge($this->files, $files);
    }

    /**
     * Записать данные об открытых на чтений файлах
     *
     * @return array
     *
     * @throws AccessToFilesException
     * @throws \Scaleplan\Redis\Exceptions\RedisSingletonException
     */
    public function allowFiles(): array
    {
        $result = [];
        switch ($this->storageType) {
            case 'redis':
                if (empty($this->storageSocketPath)) {
                    throw new AccessToFilesException('Не задан путь к сокету');
                }

                $redis = RedisSingleton::create($this->storageSocketPath);
                foreach ($this->files as $filePath) {
                    $fingerPrintData = $this->fingerPrintData;
                    $fingerPrintData[] = $filePath;
                    $key = implode(static::STORAGE_KEY_FINGERPRINT_SEPARATOR, $fingerPrintData);
                    if ($redis->set($key, time() + $this->storageTTL)) {
                        $result[] = $key;
                    }
                }

                break;

            default:
                throw new AccessToFilesException("Хранилище {$this->storageType} не поддерживается модулем");
        }

        return $result;
    }
}