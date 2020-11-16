# AccessToFiles

Control access to private files.

#### Installation

``
composer reqire scaleplan/access-to-files
``

#### How it works

Suppose we need to give access to the <b> document.pdf </b> file for <b> 1 hour </b> and to the <b> picture.jpg </b> file for 5 minutes to the currently authorized user. And these files are by default inaccessible to this user.

First, execute the following code:

```
//First file
AccessToFiles::getInstance(3600)->addFiles(['document.pdf']);

//The second file
AccessToFiles::getInstance(300)->addFiles(['picture.jpg']);
```

In this part, we create two <i> AccessToFiles </i> objects - one to open access for 1 hour - the second to open access for 5 minutes. And then add the file to each object.

The <i> AccessToFiles </i> class always creates one for the access time, which means that if we do the following after the code above:

```
$af = AccessToFiles::getInstance(3600);
```

then the new object will not be created, but only the object created above will be returned for the files available for 1 hour.

To open file access for each instance, you must execute the <i> allowFiles </i> method:

```
AccessToFiles::getInstance(3600)->allowFiles();
AccessToFiles::getInstance(300)->allowFiles();
```

This method writes metadata about files (what kind of files, for how long, to whom ...) in the metadata store, by default it's Redis.

Now, if the same user accesses these files, they will be available to him, but after the elapsed time intervals (1 hour and 5 minutes respectively) <del> the carriage again turns into a pumpkin </del> the files will be unavailable again.

For the return of temporarily open files, the lua script for nginx responds, which can climb in Redis b to check whether there is data for the requested file, if there is, it gives the file.

How does the lua script determine the user?

When writing metadata about the file <i> AccessToFiles </i> uses the <b> Finger print </b> method, it tries to collect as much data about the current user so that its user can not be confused with anyone.

By default, only the session identifier is used for this, but HTTP headers can also be used in case the session is stolen.

<b> Note: </b> the lua script stored in the project will only work with the default identification set, i.e. if only the session identifier is used, minor enhancements will be required to expand the set.


<br>

[Documentation](docs_en)
