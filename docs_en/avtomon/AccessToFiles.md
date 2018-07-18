<small> avtomon </small>

AccessToFiles
=============

Controlling access to private files

Description
-----------

Class AccessToFiles

Signature
---------

- **class**.

Constants
---------

class sets the following constants:

  - [`SERVER_FINGERPRINT_ALLOW_DATA`](#SERVER_FINGERPRINT_ALLOW_DATA) &mdash; Available data for unique customer identification
  - [`SESSION_KEY`](#SESSION_KEY) &mdash; The key of the cookie array indicating the session identifier
  - [`STORAGE_KEY_FINGERPRINT_SEPARATOR`](#STORAGE_KEY_FINGERPRINT_SEPARATOR) &mdash; Separator for unique identifier user values
  - [`ALLOW_STORAGE_TYPES`](#ALLOW_STORAGE_TYPES) ​​&mdash; Available DBMS for use

Properties
----------

class sets the following properties:

  - [`$actualServerFingerPrint`](#$actualServerFingerPrint) &mdash; On which headers to determine the authenticity of the client
  - [`$instances`](#$instances) &mdash; AccessToFiles class objects
  - [`$fingerPrintData`](#$fingerPrintData) &mdash; Data uniquely identifying the customer
  - [`$files`](#$files) &mdash; What files are opened
  - [`$storageSocketPath`](#$storageSocketPath) &mdash; The path to the socket of the DBMS storing the data of the files open for access (presumably to Redis)
  - [`$storageType`](#$storageType) &mdash; The type of DBMS storing data about files open for access
  - [`$storageTTL`](#$storageTTL) &mdash; How much it will open access to files

### `$actualServerFingerPrint`<a name="actualServerFingerPrint"> </a>

On which headers to determine the authenticity of the client

#### Signature

- **private** property.
- The value of `array`.

### `$instances`<a name="instances"> </a>

AccessToFiles class objects

#### Signature

- **private static** property.
- The value of `array`.

### `$fingerPrintData`<a name="fingerPrintData"> </a>

Data uniquely identifying the customer

#### Signature

- **private** property.
- The value of `array`.

### `$files`<a name="files"> </a>

What files are opened

#### Signature

- **private** property.
- The value of `array`.

### `$storageSocketPath`<a name="storageSocketPath"> </a>

The path to the socket of the DBMS storing the data of the files open for access (presumably to Redis)

#### Signature

- **private** property.
- The value of `string`.

### `$storageType`<a name="storageType"> </a>

The type of DBMS storing data about files open for access

#### Signature

- **private** property.
- The value of `string`.

### `$storageTTL`<a name="storageTTL"> </a>

How much it will open access to files

#### Signature

- **private** property.
- The value of `int`.

Methods
-------

Class methods class:

  - [`getInstance()`](#getInstance) &mdash; Singleton for AccessToFiles class
  - [`__construct()`](#__construct) &mdash; Constructor
  - [`setStorageSocketPath()`](#setStorageSocketPath) &mdash; Set the path to the Unix socket of the connection to the metadata store
  - [`setActualServerFingerPrint()`](#setActualServerFingerPrint) &mdash; Establish a set of headers involved in unambiguous client authentication
  - [`setStorageType()`](#setStorageType) &mdash; Set Storage Type
  - [`addFiles()`](#addFiles) &mdash; Add files to share
  - [`allowFiles()`](#allowFiles) &mdash; Write information about files open on reads

### `getInstance()`<a name="getInstance"> </a>

Singleton for AccessToFiles class

#### Signature

- **public static** method.
- It can take the following parameter (s):
  - `$storageTTL`(`int`) - time of opening access to files
  - `$actualServerFingerPrint`(`array`) - which part of the available data for user identification is used
  - `$storageSocketPath`(`string`) - path to the DBMS socket
  - `$storageType`(`string`) - DBMS type
- Can return one of the following values:
  - [`AccessToFiles`](../avtomon/AccessToFiles.md)
  - `null`
- Throws one of the following exceptions:
  - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)

### `__construct()`<a name="__construct"> </a>

Constructor

#### Signature

- **protected** method.
- It can take the following parameter (s):
  - `$actualServerFingerPrint`(`array`) - which part of the available data for user identification is used
  - `$storageSocketPath`(`string`) - path to the DBMS socket
  - `$storageType`(`string`) - DBMS type
  - `$storageTTL`(`int`) - time of opening access to files
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)

### `setStorageSocketPath()`<a name="setStorageSocketPath"> </a>

Set the path to the Unix socket of the connection to the metadata store

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$storageSocketPath`(`string`)
- Returns nothing.

### `setActualServerFingerPrint()`<a name="setActualServerFingerPrint"> </a>

Establish a set of headers involved in unambiguous client authentication

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$actualServerFingerPrint`(`array`)
- Returns nothing.

### `setStorageType()`<a name="setStorageType"> </a>

Set Storage Type

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$storageType`(`string`) - DBMS type
- Returns the `bool`value.

### `addFiles()`<a name="addFiles"> </a>

Add files to share

#### Signature

- **public** method.
- It can take the following parameter (s):
  - `$files`(`array`) - array of file paths
- Returns nothing.

### `allowFiles()`<a name="allowFiles"> </a>

Write information about files open on reads

#### Signature

- **public** method.
- Returns nothing.
- Throws one of the following exceptions:
  - [`avtomon\AccessToFilesException`](../avtomon/AccessToFilesException.md)
  - `avtomon\RedisSingletonException`

