<?php

namespace Scaleplan\AccessToFiles;

use Scaleplan\AccessToFiles\Exceptions\AccessToFilesException;
use Scaleplan\Redis\RedisSingleton;
use function Scaleplan\Helpers\get_required_env;

/**
 * Управление доступом к приватным файлам
 *
 * Class AccessToFiles
 *
 * @package Scaleplan\AccessToFiles
 */
class AccessToFiles implements AccessToFilesInterface
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
     * AccessToFiles constructor.
     *
     * @param array|null $actualServerFingerPrint - какую часть доступных данных для идентификации пользователя используем
     * @param string $storageSocketPath - путь к сокету СУБД
     * @param string $storageType - тип СУБД
     * @param int $storageTTL - время открытия доступа к файлам
     *
     * @throws AccessToFilesException
     * @throws \Scaleplan\Helpers\Exceptions\EnvNotFoundException
     */
    public function __construct(
        array $actualServerFingerPrint = null,
        string $storageSocketPath = '',
        string $storageType = '',
        int $storageTTL = 0
    )
    {
        if (!session_id()) {
            throw new AccessToFilesException('Не задан идентификатор сессии');
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

        $this->fingerPrintData = array_map(static function ($item) {
            return (string)($_SERVER[$item] ?? '');
        }, $this->actualServerFingerPrint);

        $this->fingerPrintData[] = $_COOKIE[get_required_env('PROJECT_NAME')] ?? '';
    }

    /**
     * Установить путь к Unix-сокету подключения к хранилищу метаданных
     *
     * @param string $storageSocketPath
     */
    public function setStorageSocketPath(string $storageSocketPath) : void
    {
        $this->storageSocketPath = $storageSocketPath;
    }

    /**
     * Уставновить набор заголовком, учавствующих в однозначной уатентификации клиента
     *
     * @param array $actualServerFingerPrint
     */
    public function setActualServerFingerPrint(array $actualServerFingerPrint) : void
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
    public function setStorageType(string $storageType) : bool
    {
        if ($storageType && \in_array($storageType, static::ALLOW_STORAGE_TYPES, true)) {
            return (bool)$this->storageType = $storageType;
        }

        return false;
    }

    /**
     * Добавить файлы для открытия доступа
     *
     * @param array $files
     * @param int|null $ttl
     */
    public function addFiles(array $files, int $ttl = null) : void
    {
        if ($ttl < 1) {
            $ttl = $this->storageTTL;
        }

        $this->files = array_merge($this->files, array_fill_keys($files, $ttl));
    }

    /**
     * Очистить список файлов
     */
    public function clearFiles() : void
    {
        $this->files = [];
    }

    /**
     * Записать данные об открытых на чтений файлах
     *
     * @return array
     *
     * @throws AccessToFilesException
     * @throws \Scaleplan\Redis\Exceptions\RedisSingletonException
     */
    public function allowFiles() : array
    {
        if (!$this->files) {
            return [];
        }

        $result = [];
        switch ($this->storageType) {
            case 'redis':
                if (empty($this->storageSocketPath)) {
                    throw new AccessToFilesException('Не задан путь к сокету');
                }

                $redis = RedisSingleton::create($this->storageSocketPath);
                foreach ($this->files as $filePath => $ttl) {
                    $fingerPrintData = $this->fingerPrintData;
                    $fingerPrintData[] = $filePath;
                    $key = implode(static::STORAGE_KEY_FINGERPRINT_SEPARATOR, $fingerPrintData);
                    if ($redis->set($key, time() + ($ttl ?? $this->storageTTL))) {
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
