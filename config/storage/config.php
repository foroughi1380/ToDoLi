<?php
define("STORAGE_DIR" , __DIR__ . "/../../public/files");



if (! is_dir(STORAGE_DIR)){
    mkdir(STORAGE_DIR);
}
