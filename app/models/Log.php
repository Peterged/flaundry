<?php
    #[Attribute(Attribute::TARGET_CLASS)]
    class Log {
        private App\Libraries\Database $con;
    }