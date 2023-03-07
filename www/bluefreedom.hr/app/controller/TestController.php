<?php

class TestController
{
    public function password()
    {
        echo password_hash('edunova',PASSWORD_BCRYPT);
    }
}

?>