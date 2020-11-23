<?php

/**
 * bundle ini hanya untuk dokumentasi saja,
 * bundle asli nya tidak bisa di overwrite fungsinya,
 * jadi biar jalan, harus di copy paste ke 
 *
 * cd /home/user/public_html/symfony2
 * cp -r src/Jariff/GeneratorBundle/* vendor/sensio/generator-bundle/Sensio/Bundle/GeneratorBundle/
 * 
 */
namespace Jariff\GeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JariffGeneratorBundle extends Bundle
{
    public function getParent()
    {
        return 'SensioGeneratorBundle';
    }
}
