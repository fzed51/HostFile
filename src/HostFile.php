<?php

/*
 * The MIT License
 *
 * Copyright 2015 fabien.sanchez.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace fzed51\HostFile;

/**
 * Description of HostFile
 *
 * @author fabien.sanchez
 */
class HostFile
{

    private $path;
    private $rules;

    public function __construct(/* string */$path)
    {
        $this->setPath($path);
        $this->rules = [];
    }

    private function setPath(/* string */$path)
    {
        if (!is_file($path) && !touch($path)) {
            throw new Exception\UnwritableFile("Le fichier '$path' n'e peu pas être créé.");
        }
        if (!is_readable($path)) {
            throw new Exception\UnreadableFile("Le fichier '$path' n'est pas lisible.");
        }
        $this->path = realpath($path);
    }

    function getPath()
    {
        return $this->path;
    }

    private function readFile()
    {
        $h = fopen($this->path, 'r');
        fclose($h);
    }

    private function readLine(/* string */ $line)
    {

    }

    function getRules()
    {
        return $this->rules;
    }

    function addRule(/* string */$ip, /* string */ $server_name)
    {

        return $this;
    }

    function save(/* string */$path = null)
    {
        if (is_null($path) || $path == $this->path) {
            $this->writeFile($path);
            return $this;
        } else {
            $host = clone $this;
            $host->setPath($path);
            $host->writeFile($path);
            return $host;
        }
    }

    private function writeFile(/* string */$path)
    {
        if (is_file($path)) {
            if (!is_writable($path)) {
                throw new Exception\UnwritableFile("Le fichier '$path' n'e peu pas être modifié.");
            }
        }
        $h = fopen($path, 'w');
        foreach ($this->rules as $server_name => $ip) {
            fwrite($h, "$ip $server_name \r\n");
        }
        fclose($h);
    }

}
