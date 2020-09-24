<?php
/**
 * This file contains the autoloader definition used by the
 * Enterprise package.
 * 
 * This autoloader implementation was written for the PHP
 * PSR-4 standard. Minor updates such as the addition of
 * type hints by JFLF.
 * 
 * @author Paul M. Jones
 * @author Phil Sturgeon
 * @author Larry Garfield
 * @author Jose Fernando Lopez Fernandez
 * 
 * @package Enterprise
 */

namespace Enterprise;

/**
 * PSR-4 Autoloader
 * 
 * An example of a general-purpose implementation that
 * includes the optional functionality of allowing multiple
 * base directories for a single namespace prefix.
 * 
 * Given a foo-bar package of classes in the file system at
 * the following paths ...
 * 
 *      /path/to/packages/foo-bar/
 *          src/
 *              Baz.php             # Foo\Bar\Baz
 *              Qux/
 *                  Quux.php        # Foo\Bar\Qux\Quux
 *          tests/
 *              BazTest.php         # Foo\Bar\BazTest
 *              Qux/
 *                  QuuxTest.php    # Foo\Bar\Qux\QuuxTest
 * 
 * ... add the path to the class files for the \Foo\Bar\
 * namespace prefix as follows:
 * 
 */
class Autoloader
{
    /**
     * An associative array where the key is a namespace
     * prefix and the value is an array of base directories
     * for classes in that namespace.
     * 
     * @var array $prefixes Namespace-directory map
     */
    protected array $prefixes = [ ];

    /**
     * Register the loader with the SPL autoloader stack.
     * 
     * @return void
     */
    public function register() : void
    {
        spl_autoload_register([ $this, 'loadClass' ]);
    }

    /**
     * Adds a base directory for a namespace prefix.
     * 
     * @param string $prefix The namespace prefix
     * @param string $baseDir The base directory for class
     * files in the namespace.
     * @param bool $prepend If true, add base directory to
     * the top of the stack. This causes it to be searched
     * first, instead of last.
     * 
     * @return void
     */
    public function addNamespace(string $prefix, string $baseDir, bool $prepend = false) : void
    {
        // Normalize the namespace prefix.
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize the base directory with a trailing separator.
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        // Initialize the namespace prefix array.
        if (isset($this->prefixes[$prefix]) === FALSE) {
            $this->prefixes[$prefix] = [ ];
        }

        // Retain the base directory for the namespace prefix.
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            array_push($this->prefixes[$prefix], $baseDir);
        }
    }

    /**
     * Load the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * 
     * @todo Add return type hints and documentation
     * 
     */
    public function loadClass(string $class)
    {
        // The current namespace prefix.
        $prefix = $class;

        // Work backwards through the namespace names of the
        // fully-qualified class name to find a mapped file
        // name.
        while (($position = strrpos($prefix, '\\')) !== false) {
            // Retain the trailing namespace separator in the prefix.
            $prefix = substr($class, 0, $position + 1);

            // The rest is the relative class name.
            $relativeClassName = substr($class, $position + 1);

            // Try to load a mapped file for the prefix and relative class.
            $mappedFile = $this->loadMappedFile($prefix, $relativeClassName);

            if ($mappedFile) {
                return $mappedFile;
            }

            // Since we couldn't load a mapped file, remove
            // the trailing namespace separator we added, so
            // we can reset for the next iteration of strrpos.
            $prefix = rtrim($prefix, '\\');
        }

        // Never found a mapped file.
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and
     * relative class.
     * 
     * @param string $prefix The namespace prefix.
     * @param string $relativeClassName The relative class name.
     * 
     * @todo Add return type hints and documentation.
     * 
     */
    protected function loadMappedFile(string $prefix, string $relativeClassName)
    {
        // Are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // Look through base directories for this namespace prefix.
        foreach ($this->prefixes[$prefix] as $baseDir) {
            // Replace the namespace prefix with the base
            // directory, replace the namespace separators
            // with directory separators, and finally append
            // a '.php' file extension to check whether the
            // file exists.
            $filename = $baseDir . str_replace('\\', '/', $relativeClassName) . '.php';

            // If the mapped file exists, require it.
            if ($this->requireFile($filename)) {
                // If we successfully required the file, we
                // are done.
                return $filename;
            }
        }

        // We could not find the file.
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     * 
     * @param string $filename The class file to import.
     * 
     * @return bool True if the file in question exists.
     * 
     */
    protected function requireFile(string $filename) : bool
    {
        if (file_exists($filename)) {
            require($filename);
            return true;
        }

        return false;
    }
}
