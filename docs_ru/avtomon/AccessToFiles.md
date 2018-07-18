<small>avtomon</small>

AccessToFiles
=============

Управление доступом к приватным файлам

Описание
-----------

Class AccessToFiles

Сигнатура
---------

- **class**.

Константы
---------

class устанавливает следующие константы:

- [`SERVER_FINGERPRINT_ALLOW_DATA`](#SERVER_FINGERPRINT_ALLOW_DATA) &mdash; Доступные данные для уникальной идентификации клиента
- [`SESSION_KEY`](#SESSION_KEY) &mdash; Ключ cookie-массива указывающий на идентификатор сессии
- [`STORAGE_KEY_FINGERPRINT_SEPARATOR`](#STORAGE_KEY_FINGERPRINT_SEPARATOR) &mdash; Разделитель для значений уникально идентифицирующих пользователя
- [`ALLOW_STORAGE_TYPES`](#ALLOW_STORAGE_TYPES) &mdash; Доступные СУБД для использования

Свойства
----------

class устанавливает следующие свойства:

- [`$actualServerFingerPrint`](#$actualServerFingerPrint) &mdash; По каким заголовкам определять подлинность клиента
- [`$instances`](#$instances) &mdash; Объекты класса AccessToFiles
- [`$fingerPrintData`](#$fingerPrintData) &mdash; Данные уникально идентифицирующие клиента
- [`$files`](#$files) &mdash; К каким файлам открываем доступ
- [`$storageSocketPath`](#$storageSocketPath) &mdash; Путь к сокету СУБД, хранящей данные открытых для доступа файлов (предположительно к Redis)
- [`$storageType`](#$storageType) &mdash; Тип СУБД хранящей данные об открытых для доступа файлах
- [`$storageTTL`](#$storageTTL) &mdash; На сколько будет открывать доступ к файлам

### `$actualServerFingerPrint` <a name="actualServerFingerPrint"></a>

По каким заголовкам определять подлинность клиента

#### Сигнатура

- **private** property.
- Значение `array`.

### `$instances` <a name="instances"></a>

Объекты класса AccessToFiles

#### Сигнатура

- **private static** property.
- Значение `array`.

### `$fingerPrintData` <a name="fingerPrintData"></a>

Данные уникально идентифицирующие клиента

#### Сигнатура

- **private** property.
- Значение `array`.

### `$files` <a name="files"></a>

К каким файлам открываем доступ

#### Сигнатура

- **private** property.
- Значение `array`.

### `$storageSocketPath` <a name="storageSocketPath"></a>

Путь к сокету СУБД, хранящей данные открытых для доступа файлов (предположительно к Redis)

#### Сигнатура

- **private** property.
- Значение `string`.

### `$storageType` <a name="storageType"></a>

Тип СУБД хранящей данные об открытых для доступа файлах

#### Сигнатура

- **private** property.
- Значение `string`.

### `$storageTTL` <a name="storageTTL"></a>

На сколько будет открывать доступ к файлам

#### Сигнатура

- **private** property.
- Значение `int`.

Методы
-------

Методы класса class:

- [`getInstance()`](#getInstance) &mdash; Синглтон для класса AccessToFiles
- [`__construct()`](#__construct) &mdash; Конструктор
- [`setStorageSocketPath()`](#setStorageSocketPath) &mdash; Установить путь к Unix-сокету подключения к хранилищу метаданных
- [`setActualServerFingerPrint()`](#setActualServerFingerPrint) &mdash; Уставновить набор заголовком, учавствующих в однозначной уатентификации клиента
- [`setStorageType()`](#setStorageType) &mdash; Установить тип хранилица
- [`addFiles()`](#addFiles) &mdash; Добавить файлы для открытия доступа
- [`allowFiles()`](#allowFiles) &mdash; Записать данные об открытых на чтений файлах

### `getInstance()` <a name="getInstance"></a>

Синглтон для класса AccessToFiles

#### Сигнатура

- **public static** method.
- Может принимать следующий параметр(ы):
    - `$storageTTL` (`int`) - время открытия доступа к файлам
    - `$actualServerFingerPrint` (`array`) - какую часть доступных данных для идентификации пользователя используем
    - `$storageSocketPath` (`string`) - путь к сокету СУБД
    - `$storageType` (`string`) - тип СУБД
- Может возвращать одно из следующих значений:
    - [`AccessToFiles`](../avtomon/AccessToFiles.md)
    - `null`
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)

### `__construct()` <a name="__construct"></a>

Конструктор

#### Сигнатура

- **protected** method.
- Может принимать следующий параметр(ы):
    - `$actualServerFingerPrint` (`array`) - какую часть доступных данных для идентификации пользователя используем
    - `$storageSocketPath` (`string`) - путь к сокету СУБД
    - `$storageType` (`string`) - тип СУБД
    - `$storageTTL` (`int`) - время открытия доступа к файлам
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)

### `setStorageSocketPath()` <a name="setStorageSocketPath"></a>

Установить путь к Unix-сокету подключения к хранилищу метаданных

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$storageSocketPath` (`string`)
- Ничего не возвращает.

### `setActualServerFingerPrint()` <a name="setActualServerFingerPrint"></a>

Уставновить набор заголовком, учавствующих в однозначной уатентификации клиента

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$actualServerFingerPrint` (`array`)
- Ничего не возвращает.

### `setStorageType()` <a name="setStorageType"></a>

Установить тип хранилица

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$storageType` (`string`) - тип СУБД
- Возвращает `bool` value.

### `addFiles()` <a name="addFiles"></a>

Добавить файлы для открытия доступа

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$files` (`array`) - массив путей к файлам
- Ничего не возвращает.

### `allowFiles()` <a name="allowFiles"></a>

Записать данные об открытых на чтений файлах

#### Сигнатура

- **public** method.
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)
    - `avtomon\RedisSingletonException`

