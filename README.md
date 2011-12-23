================================================================================
================================================================================
             (                            (
             )\ )                         )\ )
            (()/(        (       )  (    (()/(  (   (      (
             /(_))`  )   )(   ( /(  )\ )  /(_)) )\  )(    ))\
            (_))  /(/(  (()\  )(_))(()/( (_))_|((_)(()\  /((_)
            / __|((_)_\  ((_)((_)_  )(_))| |_   (_) ((_)(_))
            \__ \| '_ \)| '_|/ _` || || || __|  | || '_|/ -_)
            |___/| .__/ |_|  \__,_| \_, ||_|    |_||_|  \___|
                 |_|                |__/

The wonderful SprayFire ASCII logo was created by http://patorjk.com/software/taag/
================================================================================
================================================================================

SprayFire is a fully unit-tested, light-weight PHP framework for developers who
want to make simple, secure, dynamic website content.  Some of the features built
into SprayFire will include:

- Full support of PHP 5.3+, most importantly including the use of namespaces
- Prepared statements for all CRUD functionality
- Automatic escaping of output on responses
- A simple but flexible templating system using built in PHP functionality
- An interface driven, fully extensible API allowing virtually any component of
the framework to be implemented at the application's needs
- A completely domain driven Model interpretation utilizing a custom-built data
persistence solution.
- Easy support for outside plugins and libraries that support PHP 5.3+.
- A reimagining of the normal "MVC" design pattern into something we here at SprayFire
like to call "MRC", or Model-Response-Controller.  Please check out the wiki for
more information on exactly how SprayFire interprets this design pattern.

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

SprayFire is currently in development stage, the interface and API provided by the
framework is completely subject to change at this point and nothing found in the
code at this point is considered 'stable'.  At the moment, consider SprayFire to
be in a very 'volatile' state.

The framework wiki is a more reliable, stable source for information on the current
state of the framework and the future of SprayFire.

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


Please check out the [framework wiki](https://github.com/cspray/SprayFire/wiki/) for more information!

Please check out the [API documentation](https://cspray.github.com/SprayFire

Testing Instructions
================================================================================
Framework objects that can be reasonably unit tested will have those tests stored
in {framework_dir}/tests/.  At the moment all tests have to be run individually,
using the following directions:

1. Ensure PHPUnit is installed and in the include path on your computer.
2. Change directory to {framework_dir}/tests/.
3. Run each test by inputting the following command in your terminal

phpunit --bootstrap test_bootstrap.php {ClassNameTest} /path/to/{ClassNameTest}.php

Please note that there is an AllTests suite that will, well, run all tests.

The SprayFire development team consists of:
================================================================================

Charles Sprayberry
Lead Developer and Creator
Benevolent Dictator for Life
twitter: @charlesspray
github: cspray/SprayFire