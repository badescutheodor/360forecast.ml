<?php

return [
    /**
     * ==========================================
     * The secret for symmetric cookie encryption
     * ==========================================
     */

    'secret' => getenv('SECRET') ? getenv('SECRET') : 'def000004b162c9ed413c2da428937ba7c9656563c204c47bc150146c3714a39f38ef5657222940c6a0e8c3f252e864dd17466eda37b98767b9a524c575eaefb19d344df'
];