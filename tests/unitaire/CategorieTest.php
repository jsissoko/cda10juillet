<?php

namespace App\Tests;

use App\Entity\Categories;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    public function testNomCategorieAccessors()
    {
        $categorie = new Categories();
        $nom = "Parfum";

        $categorie->setNomCategorie($nom);
        $this->assertSame($nom, $categorie->getNomCategorie());
    }
}
