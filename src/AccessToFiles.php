<?php

namespace avtomon;

class AccessToFilesException extends CustomException
{
}

class AccessToFiles
{
    /**
     * Доступные данные для уникальной идентификации клиента
     *
     * @const array
     */
    const SERVER_FINGERPRINT_ALLOW_DATA = [
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
    const SESSION_KEY = 'qooiz';

    /**
     * Разделитель для значений уникально идентифицирующих пользователя
     *
     * @const string
     */
    const STORAGE_KEY_FINGERPRINT_SEPARATOR = ':';

    /**
     * Доступные СУБД для использования
     */
    const ALLOW_STORAGE_TYPES = ['redis'];

    /**
     * Объект класса AccessToFiles
     *
     * @var null|AccessToFiles
     */
    private static $instance = null;

    /**
     * Данные уникально идентифицирующие клиента
     *
     * @var array
     */
    private $fingerPrintData = [];

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
     * @param array|null $actualServerFingerPrint - какую часть доступных данных для идентификации пользователя используем
     * @param string $storageSocketPath - путь к сокету СУБД
     * @param string $storageType - тип СУБД
     * @param int $storageTTL - время открытия доступа к файлам
     *
     * @return AccessToFiles|null
     */
    public static function create(
        array $actualServerFingerPrint = null,
        string $storageSocketPath = '',
        string $storageType = '',
        int $storageTTL = 0
    )
    {
        if (is_null(self::$instance)) {
            self::$instance = new AccessToFiles($actualServerFingerPrint, $storageSocketPath, $storageType, $storageTTL);
        }

        return self::$instance;
    }

    /**
     * AccessToFiles constructor.
     *
     * @param array|null $actualServerFingerPrint - какую часть доступных данных для идентификации пользователя используем
     * @param string $storageSocketPath - путь к сокету СУБД
     * @param string $storageType - тип СУБД
     * @param int $storageTTL - время открытия доступа к файлам
     *
     * @throws AccessToFilesException
     */
    private function __construct(
        array $actualServerFingerPrint = null,
        string $storageSocketPath = '',
        string $storageType = '',
        int $storageTTL = 0
    )
    {
        if (empty($_COOKIE[self::SESSION_KEY])) {
            throw new AccessToFilesException('Не задан ключ для доступа к идентификатору сессии');
        }

        if ($storageSocketPath) {
            $this->storageSocketPath = $storageSocketPath;
        }

        if ($storageType && in_array($storageType, self::ALLOW_STORAGE_TYPES)) {
            $this->storageType = $storageType;
        }

        if ($storageTTL) {
            $this->storageTTL = $storageTTL;
        }

        if (is_null($actualServerFingerPrint)) {
            $this->actualServerFingerPrint = $actualServerFingerPrint ?? ($this->actualServerFingerPrint ?: self::SERVER_FINGERPRINT_ALLOW_DATA);
        }

        $this->fingerPrintData = array_map(function ($item) {
            return $_SERVER[$item] ?? '';
        }, $this->actualServerFingerPrint);

        $this->fingerPrintData[] = $_COOKIE[self::SESSION_KEY];
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
        if ($storageType && in_array($storageType, self::ALLOW_STORAGE_TYPES)) {
            return (bool) $this->storageType = $storageType;
        }

        return false;
    }

    /**
     * Добавить файлы для открытия доступа
     *
     * @param array $files - массив путей к файлам
     */
    public function addFiles(array $files)
    {
        $this->files = array_merge($this->files, $files);
    }

    /**
     * Записать данные об открытых на чтений файлах
     *
     * @throws AccessToFilesException
     * @throws \avtomon\RedisSingletonException
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
                    array_push($fingerPrintData, $filePath);
                    $key = implode(self::STORAGE_KEY_FINGERPRINT_SEPARATOR, $fingerPrintData);
                    if ($redis->set($key, time() + $this->storageTTL)) {
                        $result[] = $key;
                    }
                }

                break;
        }

        return $result;
    }
}