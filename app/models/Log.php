<?php
    #[Attribute(Attribute::TARGET_CLASS)]
    class Log {
        private App\libraries\Database $con;
    }