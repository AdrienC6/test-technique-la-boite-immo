<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\Gateway;
use App\Enum\PropertyType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $seloger = new Gateway();
        $seloger->setName('SeLoger');
        $seloger->setCode('seloger');
        $seloger->setActive(true);
        $seloger->setConfig([
            'api_key' => 'SL_' . md5('seloger_secret_key'),
            'api_url' => 'https://api.seloger.com/v2/listings',
            'export_limit' => 50,
            'contact_email' => 'api@seloger.com',
        ]);
        $manager->persist($seloger);

        $leboncoin = new Gateway();
        $leboncoin->setName('LeBonCoin');
        $leboncoin->setCode('leboncoin');
        $leboncoin->setActive(true);
        $leboncoin->setConfig([
            'api_key' => 'LBC_' . md5('leboncoin_secret_key'),
            'api_url' => 'https://api.leboncoin.fr/api/v3/properties',
            'export_limit' => 100,
            'notification_webhook' => 'https://webhook.leboncoin.fr/callbacks',
        ]);
        $manager->persist($leboncoin);

        $zillow = new Gateway();
        $zillow->setName('Zillow');
        $zillow->setCode('zillow');
        $zillow->setActive(false);
        $zillow->setConfig([
            'api_key' => 'ZILLOW_' . md5('zillow_secret_key'),
            'api_url' => 'https://api.zillow.com/v1/properties',
            'export_limit' => 100,
            'notification_webhook' => 'https://webhook.zillow.com/callbacks',
        ]);
        $manager->persist($zillow);

        // Create an apartment in Paris
        $property1 = new Property();
        $property1->setTitle('Appartement lumineux au cœur de Paris');
        $property1->setDescription('Magnifique appartement rénové de 65m² avec balcon donnant sur une cour arborée. Cuisine équipée, salon spacieux, chambre avec dressing.');
        $property1->setPrice(425000);
        $property1->setAddress('15 rue des Martyrs');
        $property1->setCity('Paris');
        $property1->setZipCode('75009');
        $property1->setCountry('FR');
        $property1->setSurface(65);
        $property1->setNumberOfRooms(2);
        $property1->setPropertyType(PropertyType::APARTMENT);
        $property1->setIsPublished(true);
        $manager->persist($property1);

        // Create a house in Lyon
        $property2 = new Property();
        $property2->setTitle('Maison familiale avec jardin à Lyon');
        $property2->setDescription('Belle maison de 120m² avec jardin de 300m². 4 chambres, 2 salles de bain, grande cuisine ouverte sur séjour. Garage pour 2 voitures et cave.');
        $property2->setPrice(595000);
        $property2->setAddress('42 avenue des Cerisiers');
        $property2->setCity('Lyon');
        $property2->setZipCode('69005');
        $property2->setCountry('FR');
        $property2->setSurface(120);
        $property2->setNumberOfRooms(5);
        $property2->setPropertyType(PropertyType::HOUSE);
        $property2->setIsPublished(true);
        $manager->persist($property2);

        // Create a commercial property in Marseille
        $property3 = new Property();
        $property3->setTitle('Local commercial idéalement situé');
        $property3->setDescription('Local commercial de 85m² en rez-de-chaussée dans une rue commerçante. Grande vitrine, arrière-boutique avec sanitaires et petit espace cuisine.');
        $property3->setPrice(275000);
        $property3->setAddress('103 rue de la République');
        $property3->setCity('Marseille');
        $property3->setZipCode('13001');
        $property3->setCountry('FR');
        $property3->setSurface(85);
        $property3->setNumberOfRooms(2);
        $property3->setPropertyType(PropertyType::COMMERCIAL);
        $property3->setIsPublished(false); // Not published yet
        $manager->persist($property3);

        $manager->flush();
    }
}
