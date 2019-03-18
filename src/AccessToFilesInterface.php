<?php

namespace Scaleplan\AccessToFiles;


use Scaleplan\AccessToFiles\Exceptions\AccessToFilesException;

/**
 * Управление доступом к приватным файлам
 *
 * Class AccessToFiles
 *
 * @package Scaleplan\AccessToFiles
 */
interface AccessToFilesInterface
{
    /**
     * Установить путь к Unix-сокету подключения к хранилищу метаданных
     *
     * @param string $storageSocketPath
     */
    public function setStorageSocketPath(string $storageSocketPath) : void;

    /**
     * Уставновить набор заголовком, учавствующих в однозначной уатентификации клиента
     *
     * @param array $actualServerFingerPrint
     */
    public function setActualServerFingerPrint(array $actualServerFingerPrint) : void;

    /**
     * Установить тип хранилица
     *
     * @param string $storageType - тип СУБД
     *
     * @return bool
     */
    public function setStorageType(string $storageType) : bool;

    /**
     * Добавить файлы для открытия доступа
     *
     * @param array $files - массив путей к файлам
     */
    public function addFiles(array $files) : void;

    /**
     * Записать данные об открытых на чтений файлах
     *
     * @return array
     *
     * @throws AccessToFilesException
     * @throws \Scaleplan\Redis\Exceptions\RedisSingletonException
     */
    public function allowFiles() : array;
}
