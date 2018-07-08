<small>avtomon</small>

AccessToFiles
=============

Усравление доступом к приватным файлам

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
- [`$instance`](#$instance) &mdash; Объект класса AccessToFiles
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

### `$instance` <a name="instance"></a>

Объект класса AccessToFiles

#### Сигнатура

- **private static** property.
- Может быть одного из следующих типов:
    - `null`
    - [`AccessToFiles`](../avtomon/AccessToFiles.md)

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

- [`create()`](#create) &mdash; Синглтон для класса AccessToFiles
- [`__construct()`](#__construct) &mdash; AccessToFiles constructor.
- [`setStorageType()`](#setStorageType) &mdash; Установить тип хранилица
- [`addFiles()`](#addFiles) &mdash; Добавить файлы для открытия доступа
- [`allowFiles()`](#allowFiles) &mdash; Записать данные об открытых на чтений файлах

### `create()` <a name="create"></a>

Синглтон для класса AccessToFiles

#### Сигнатура

- **public static** method.
- Может принимать следующий параметр(ы):
    - `$actualServerFingerPrint` (`array`) &mdash; - какую часть доступных данных для идентификации пользователя используем
    - `$storageSocketPath` (`string`) &mdash; - путь к сокету СУБД
    - `$storageType` (`string`) &mdash; - тип СУБД
    - `$storageTTL` (`int`) &mdash; - время открытия доступа к файлам
- Может возвращать одно из следующих значений:
    - [`AccessToFiles`](../avtomon/AccessToFiles.md)
    - `null`
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)

### `__construct()` <a name="__construct"></a>

AccessToFiles constructor.

#### Сигнатура

- **private** method.
- Может принимать следующий параметр(ы):
    - `$actualServerFingerPrint` (`array`) &mdash; - какую часть доступных данных для идентификации пользователя используем
    - `$storageSocketPath` (`string`) &mdash; - путь к сокету СУБД
    - `$storageType` (`string`) &mdash; - тип СУБД
    - `$storageTTL` (`int`) &mdash; - время открытия доступа к файлам
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)

### `setStorageType()` <a name="setStorageType"></a>

Установить тип хранилица

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$storageType` (`string`) &mdash; - тип СУБД
- Возвращает `bool` value.

### `addFiles()` <a name="addFiles"></a>

Добавить файлы для открытия доступа

#### Сигнатура

- **public** method.
- Может принимать следующий параметр(ы):
    - `$files` (`array`) &mdash; - массив путей к файлам
- Ничего не возвращает.

### `allowFiles()` <a name="allowFiles"></a>

Записать данные об открытых на чтений файлах

#### Сигнатура

- **public** method.
- Ничего не возвращает.
- Выбрасывает одно из следующих исключений:
    - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)
    - `avtomon\RedisSingletonException`

