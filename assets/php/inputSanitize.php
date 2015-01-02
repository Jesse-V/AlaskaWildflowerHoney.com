<?php

    //counter XSS on given array
    //https://stackoverflow.com/questions/1996122/how-to-prevent-xss-with-html-php
    function sanitizeArray($pageInput)
    {
        $cleaned = array();
        foreach ($pageInput as $key => $value)
            $cleaned[$key] = is_array($value) ? sanitizeArray($value) : sanitizeVar($value);
        return $cleaned;
    }



    function sanitizeVar($x)
    {
        return htmlspecialchars($x, ENT_QUOTES, 'UTF-8');
    }

?>
