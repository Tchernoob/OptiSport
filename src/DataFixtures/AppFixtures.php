<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\User;
use App\Entity\Region;
use App\Entity\Department;
use App\Repository\PartnerRepository;
use App\Entity\Template;
use App\Entity\Mods;
use App\Entity\TemplateMods;
use App\Entity\ClientMods;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $userPasswordHasherInterface;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface, PartnerRepository $repo)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->repo = $repo;
    }

    public function load(ObjectManager $manager): void
    {

        $admin = new User();
        $admin
            ->setFirstName('Théo')
            ->setLastName('Pichon')
            ->setPassword($this->userPasswordHasherInterface->hashPassword($admin, "Z&nFurieux$3000"))
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('tpichon@optilian.com')
            ->setCreatedAt(new \DateTime());

        $manager->persist($admin);

        $partners = [
            //name, is_active, email, summary, description, url, logo
            ['La cerise Verte',
                1,
                'Sport - Passion - Cerise',
                'L’enseigne la Cerise Verte est une licence de marque française de clubs de fitness dont la société mère, 
                 OB Réseaux a son siège social à Rennes (Ille-et-Vilaine, Bretagne).
                 Elle est spécialisée dans la culture physique et la remise en forme à petits prix. 
                 Fondée en 1996 par l\'entrepreneur autodidacte Thierry Marquer, elle compte 20 ans plus tard en 2016 un réseau de plus de 300 clubs. 
                 Dès 2006, Thierry Marquer s\’est lancé dans une exploitation sous licence de sa marque. 
                 Il poursuit depuis son développement en France et à l\'étranger (Espagne, Italie) avec un objectif de 1000 clubs',
                'https://www.cerise-verte.com',
                'https://nuts.com/driedfruit/glazedfruit/green-cherries.html',
            ],

            ['Le Melon Pourpre',
                0,
                'Sport - Passion - Melon',
                'L’enseigne le Melon Pourpre est une licence de marque française de clubs de fitness dont la société mère, 
                 OB Réseaux a son siège social à Rennes (Ille-et-Vilaine, Bretagne).
                 Elle est spécialisée dans la culture physique et la remise en forme à petits prix. 
                 Fondée en 1996 par l\'entrepreneur autodidacte Thierry Marquer, elle compte 20 ans plus tard en 2016 un réseau de plus de 300 clubs. 
                 Dès 2006, Thierry Marquer s\’est lancé dans une exploitation sous licence de sa marque. 
                 Il poursuit depuis son développement en France et à l\'étranger (Espagne, Italie) avec un objectif de 1000 clubs',
                'https://www.melon-pourpre.com',
                'data:image/jpeg;base64,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',
            ],

            ['Tomate',
                1,
                'Sport - Passion - Tomate',
                'L’enseigne la Tomate  est une licence de marque française de clubs de fitness dont la société mère, 
                 OB Réseaux a son siège social à Rennes (Ille-et-Vilaine, Bretagne).
                 Elle est spécialisée dans la culture physique et la remise en forme à petits prix. 
                 Fondée en 1996 par l\'entrepreneur autodidacte Thierry Marquer, elle compte 20 ans plus tard en 2016 un réseau de plus de 300 clubs. 
                 Dès 2006, Thierry Marquer s\’est lancé dans une exploitation sous licence de sa marque. 
                 Il poursuit depuis son développement en France et à l\'étranger (Espagne, Italie) avec un objectif de 1000 clubs',
                'https://www.melon-pourpre.com',
                'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAoHCBESEhISERIPDxERDw8PEREREREPEREPGBQZGRgUGBgcIS4lHB4rHxgYJjgmKy8xNTU1GiQ7QDszPy40NTEBDAwMEA8QGhISGDQhISE0NDQxNDE0NDQ0NDQxMTQ0NDQ0MTE0NDQ0MTExNDQ0MTQ0NDE0NDExNDQ0NDQxNDQ0NP/AABEIAO0A1QMBIgACEQEDEQH/xAAcAAACAwEBAQEAAAAAAAAAAAAABAIDBQEGBwj/xAA+EAACAgAEAwUECAQEBwAAAAAAAQIDBBESIQUTMQZBUWFxBzJSkSJCcoGhsbLBI2KC4SRDc5IURFNjotHx/8QAGQEAAwEBAQAAAAAAAAAAAAAAAAECAwQF/8QAKhEAAgIBAwQBAgcBAAAAAAAAAAECEQMSITEEQVFhIhMyFEJxgbHB0QX/2gAMAwEAAhEDEQA/APswAAAAAAAAAAAAAAAAAAABGTSWb2S3JHjO1naNV2wwlb+lJa7pL6se6Hq+rIyT0RcjTFjeSaij0FOO1Wbe50RpnjsNiktO/genwWIU4+a6nD0XUSnKUZ8vdf2jfqcGimlsNAAHonIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFGIxMa1nJ5Z9Et2zOu41Fe7Fvzk0i445y4RpDFOf2o2APJYrtBdvlpj6bmJi+NWveU2/WR0x6Kb3bSNPw8ly6Pc8R4hCmuyblHOEJSSzTbeWy+Z8gtxCdjslLVOUtUm+uZfxTittkHDNtNrPzSeZ5nEUYiVmaScW0tn082jg6zpcjklBNpLn2dOBwxJtO2z2WBxc7ZZxeUIvJy/ZHr+G8UdayUU08s2+r+88Jw6zRGMIpbd7b3fezcw+In4R+Z6PTf8AMw44JtXLu/8APH8lPJ9XaW6Pd4filc+ucX59PmOQnGW6afo8zw9WJl4fiN04uxPZZejHPo/Doyl0sX9ro9iB5+jiti95KXr1NCnicH7ycX+BzSwTj2swl0+SPazQArrsUlnFprxRYYmAAAAAAAAAAAAAAAAAAAAAAAAAFOJvVcXJ/cu9s7daoRcpPJI8txLiEpyeXRdPBG2HC8j9G2HFrdvgnjcY5ybf3LuS8DIxONS2z+RGzXLqyr/hX1Z60McYo9C3VRVIRvxEpdNhC7z3ZpYiKQnKrUy5I55CCrzJqh9TQWHyQ3Rh047rclQ8iUOxnUwXeh6mDXRkXh8mNUxNEioovps+JD9TT6MXrrzL44fw2MptHRHUvYxFF0WLwjJeZfGRgym7GaLZRecXl4+DNWjGxl1+i/HuMaJbFnPkgpcnPlxxnyb50xqcRKPR7eD6f2HqMXGe3R+D7/RnNLHJHHPFKPsbAAMzIAAAAAAAAAAAACu2xRTlJ5JBZYopybySWbZg43FSsfhFdF+/qaY8bm/RrixPI/RDH4uVj22S6LwM+VXj8hvIqkj0YVFUj1YJRVJbFEa/kVYyxRWS6v8AAvslkhF1uT8WbQ3dsjI+yEnS5FkcOl0+9mhCnIJVl60KOOhB0jdVH0SegYri9IpT2HGPyF7MPnuUKvI1IwK50bkLJ2HKKuymkbgiuNZfFESY1wTjAnozCBYkYtmbZBRaLEdyOOJNk3Z1I5NZgGefqHAcDnDsU/ck8/hb6vyZqHnJL7ma2AxWtaZe8l814mGbH+ZHNnxV8o/uOgAHOcoAAAAHDpj8Wxu/Kg9/rvwXh6lwg5ukXCDnJRQtj8U7JZR9yL2838RSonYRyOtHckkqXB6SSitK7EGiqZfIpmUjSItNZv0LIx2CES5RNGwXkqcCuUNxlog0JMpMXcMmNxjsVyiW1LYUnsSwSJ6M0dSJqJDZDZToBIv0leWQWO7JxJxRys6QyGTyA6gJIIyjmVPYtZGTzKRSIZ5kFNxaktsnmmEiDZaRaRv4LEqyOfRraS8GNGBwu3RYk+k1l9/cb5w5YaZbHn58eiVLgAADMxEeJYzlR23nLaK8/F+SMKtd73bebb6t+JzG3ynbPPpGTil4JPInA9DHj0R9s9PFiWOF92WxONnHIg2VRdEmyqZJsr1blItEoxJtBWu8kxNibIZHcjp1oAsqaLKiJOrqD4B8FmRDEzcY5osk9zl8dUGSuVZCe6sVwmK1ddh2aMLPS2vka+Fs1QWfVF5YVui8kK3RZHYm2VTJKZm0ZNFiZIrTOqRNCoGQkSbISY0UiuRTJl0mU2rvNUaI5raykuqf4o9Rh7VKEZfEkzyOrqja7PYjOEoPrF6l9l/3Mupx3DUuxh1ULxqXg2gADgPOPGzedln+rZ+pl6YkpfTn/q2fqZcpnrtWe0t0i5yDMpcyqVgKIOkXymEdyhMYp8QapCTL+mwNkczqZmKjqJSIJnZsQiJZEpzLFIbGywsRRmSUiWiGhXH4PUs4bNFvD4Sisn1GlNZZHOg9bcdLDW3HSyM2VxkdciuTBIpIZi80RciqMwlIVC0k3IjqK02+hbyth7IeyK5MrbO27MqbLSNEhecsmX8LxXLvi2/oyfLl6Po/nkK4t94nOzbzRtpUotPuRJXcX3PpICXCcVzaYT73FKX2lswPFap0zx3s6Z4muz+JNfz2P/zY1ryMmif8WXm7P1sbumeyuEeynUS6VoRkKRkXQeZdGdjMWNx6C9SSLYyMpGsY7F2YENRzURQ6LNQSZWmEmOhUdzLIMX1EoTBoBjUGoqzO5k0FFqkccyvMGwoVE9RXYw1Fc5lJDo7Cwmp5ieslCZWkSZpUxLnIqpmnEhKZhVshq2QxniKOY1Y80zPcjbGtqNY/aV4l5pmZr6oetmZWIllL7zbgifk9H2Y4q6o2QackpRkvLNPP8gGuxVCdds39acYr+lP/ANgebncPqO0cGVw1u0eTw8/4kn52/qHJzM5QcLZwfWFlsH/uGJT2O2L2R2reJfCYzh59xnRmM4ae5aEmaSkWKQtCe5dqCjZMt1ApEEwzFQ7J6iMpkcyuyQUJsk5E65C0pk6plNGZodxBM5KexWpmaRqti7M42VayLmGkNi1yIyZS5nVMrSFlUpZMjzCN0tymUyzF7Gxg7dsiyUtzKw1+Uh6U8zJx3LRZKZnXTyYxKYjiJFR2HZXOZmYx9PkNTmKYndMqXBEt0fQ+yNOWErb6z1Tf3/8AwDS4ZTopqh8NcF9+W4HizblJs8mXybZ877SVcvG3LoptWr+pb/imZtlh6Ht/Tpvqs+OtxfrF/wBzydk9z0cUrxo9DHK8aG4zGsPPcy4zGaZmqZUXua8J7jKmZkLNxiMyjax5TOaxZTDmAFjOshbMqUiu2YxMlKZ2qYo7CVcwsjuafM2I8wWUzjmM1sb1nHMWVhxzABhzIuwXcwUwsLO3TKdZCyRTKYjJ8l+vJo0qLczFlMvpvyJYJmlbYJ2zK7LiidgFEbZHeH18y+qHXXbWn6aln+AvZM2uxWG5mKU+6qDn/U9l+5OSWmLZGSWmLZ9JAAPIPLPIe0OnPD1Wf9O5J+kotfmkfOLJ7n2DtLgZYjC21wSc3FSgntnKLUkvwPjNyabUk1KLaaezTXVHb07uFeGdeF3CvAxGwYqmZsZl1dh0JmqZswmMxsMyE+gxGwqzax1TJaxNTOqYDscjMqsmVKwjJ5jsT34DWdjZuLTkRjPcLIs1IzIuYvCZyUx2aJjLmR5hRrIuYx2Ncw5GYrrI8wBWX3TF5TOznmLymTZnItdh2uwWcyEJ7isV7j0rSLmL2SKLcQNltll9+R9B9neF04edzW9tjS+xHb88z5ZKbk0lvKTUUvFt5JH3Tg2DVGHpqX+XVCL+1lu/nmcvVT+KXk5c89qHgADhOQD537Qez+nPGUx26YiKXTwsX7n0QrsrjKLjJKUZJxknumn1TKhNwdoqMnF2j8/OZOEzS7a8Bngb3oTeHtblVL4fGD81+RhVX55HcpJ7o6lJPg2oTGIzM2My+EzSzex7Wc5opzDvMHYWN8wlGwR5hNWBY0y2yZXGwXssIRmFkN7mlCwnKZnwsLXMaZcXsMOZHWLOZHWOwsacyPMF9ZxzCxWM8wrsmUcw7OYrB7o45nYy3FbJkecTZmmNYi/uEbbiqy0UstByCUj03YnCf8Rj6YPeNcndP0huvxyPuR8x9j+AzWJxLXWUcPB+i1Ty+cT6ccGaVy/Q5MjuQAAGRmAAAAZfH+EV4zD2UWbak9Eu+Fn1ZL0Z8A4rgrcJdOm1aZwlk/CS7pLyZ+kzxXtF7LLG0c2pf4mhNxy/zId8H596/uaY51s+C4SrY+S4fFZrIertTR5lWOLyeaabTz2aa6pj9GJOtS7HVGfY2lYDsM9XHeeVZeof1gpiPPOxvHYWMzmR1i8rUR5otRL5HIzGNexnV2JjHM2KTHFl2o45C0riuVwWOxzWRlcJO4rdoWLUPc0hZiROVotO3MlyolyHJXlfN2FHMhKwlSJstnYanZvs7iOIW6KlphFrmXSX0YR/eXkX9j+yd3EbM968NCWVl2XV98YeMvyPufCuGU4WqNNEFCEV072++Tfe34mM8tbLkzlOivgXCKsHRDD1Z6YZtt+9Ob3lJ+bZpgBzGAAAAAAAAAAAAB4Ptl7PKcZquw2nD4l5ye2Vdsv5kuj80fGsdgcRhLHVia502Lul0a8YtbSXmj9QmTx/gGGx1bqxMFJfVktpwfxRl3Fxm0XGbR+dIXk+abfa7sPiuHN2RzxGFz2tjH6UF4Tiunr0PLcw6FOzZSse5p1XCHMOcweodmg7cznMyEVYSlYDkFj0LxjnmOrC2FwKQKQ9K0i7RSVhW7R6h2Ou0g7RN2HNYtQrG5WlM7CjWW4XDW3S0U12XS8IQlNr1y6Etkt2cdh7XsN2Jsx8ldepVYSLzz6Sufww/l8X8jQ7E+zayycbuIRlXXBqUcO2tdj6rXl0j5d59iqrjCKjFKMYpJRSySS7kjKWTwZufgrweErprjXVGMK4RUYxiskkMABkZgAAAAAAAAAAAAAAAAAAAEJwUk4ySlFrJppNNeDR8v7ZezGE9V/D0oT3lLDN5Vzffof1X5dPQ+pgNNrgadH5axPBsZXnzMJioZdXKi3L55ZCE9UfejKP2k4/mfrQotw1dnv1wn9uMZfmi9fov6h+TZyfcwrtfRn6gxPZfh9mevCYaWf/AG4r8jKxPs54RP8A5WMPOuc4P8GGsNZ+d3IFM+5Yn2R8Nl7k8VVl8Nil+pMwuI+yOqtN14y5eU6oS/JoNQ9SPlsbAcz6t2f9lGHmuZiMTbak2tFcI0p5eLzkz3nCeyHDsNlysLVqX15rmS+bDWDnR8C4X2dx2Kf+Hwt80/ruDrr/AN8ske04V7I8TPJ4q6uhd8a87Z/PZH2jLLoSE5slzZ4nhfsy4ZTk51yxMl33Sco5/ZWSPW4XB1VRUaq66orpGEIwXySGQIbbIsAAAAAAAAAAAAAAAA//2Q==',

            ],

            ['Carotte',
                0,
                'Sport - Passion - Carotte',
                'L’enseigne la Carotte  est une licence de marque française de clubs de fitness dont la société mère, 
                 OB Réseaux a son siège social à Rennes (Ille-et-Vilaine, Bretagne).
                 Elle est spécialisée dans la culture physique et la remise en forme à petits prix. 
                 Fondée en 1996 par l\'entrepreneur autodidacte Thierry Marquer, elle compte 20 ans plus tard en 2016 un réseau de plus de 300 clubs. 
                 Dès 2006, Thierry Marquer s\’est lancé dans une exploitation sous licence de sa marque. 
                 Il poursuit depuis son développement en France et à l\'étranger (Espagne, Italie) avec un objectif de 1000 clubs',
                'https://www.carot.com',
                'data:image/jpeg;base64,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',
            ],

            ['Patate',
                1,
                'Sport - Passion - Patate',
                'L’enseigne la Papate  est une licence de marque française de clubs de fitness dont la société mère, 
                 OB Réseaux a son siège social à Rennes (Ille-et-Vilaine, Bretagne).
                 Elle est spécialisée dans la culture physique et la remise en forme à petits prix. 
                 Fondée en 1996 par l\'entrepreneur autodidacte Thierry Marquer, elle compte 20 ans plus tard en 2016 un réseau de plus de 300 clubs. 
                 Dès 2006, Thierry Marquer s\’est lancé dans une exploitation sous licence de sa marque. 
                 Il poursuit depuis son développement en France et à l\'étranger (Espagne, Italie) avec un objectif de 1000 clubs',
                'https://www.carot.com',
                'data:image/jpeg;base64,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',
            ],

        ];

        foreach($partners as $partner)
        {
            $prtner = new Partner();
            $prtner->setName($partner[0])
                    ->setIsActive($partner[1])
                    ->setSummary($partner[2])
                    ->setDescription($partner[3])
                    ->setUrl($partner[4])
                    ->setLogo($partner[5])
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime());

                $manager->persist($prtner);
                $manager->flush($prtner);

        }

        $dptAuvergne =
            [
                '01' => 'Ain',
                '03' => 'Allier',
                '07' => 'Ardèche',
                '15' => 'Drôme',
                '38' => 'Isère',
                '42' => 'Loire',
                '43' => 'Haute-Loire',
                '63' => 'Puy-de-Dôme',
                '69'=> 'Rhône',
                '73'=> 'Savoie',
                '74'=> 'Haute-Savoie',

            ];

        $auvergne = new Region();
        $auvergne->setName('Auvergne-Rhône-Alpes');
        $manager->persist($auvergne);

        foreach($dptAuvergne as $code=>$name)
        {
            $dptauv = new Department();
            $dptauv->setCode($code)
                ->setName($name)
                ->setRegion($auvergne);

            $manager->persist($dptauv);
        }


        $dptBourgogne =
            [
                '21' => 'Côte-d\'Or',
                '25' => 'Doubs',
                '39' => 'Jura',
                '58'=>'Nièvre',
                '70'=> 'Haute-Saône',
                '71'=> 'Saône-et-Loire',
                '89'=> 'Yonne',
                '90'=> 'Territoire de Belfort',
            ];

        $bourgogne = new Region();
        $bourgogne->setName('Bourgogne-Franche-Comté');
        $manager->persist($bourgogne);

        foreach($dptBourgogne as $code=>$name)
        {
            $dptbour = new Department();
            $dptbour->setCode($code)
                ->setName($name)
                ->setRegion($bourgogne);

            $manager->persist($dptbour);
        }

        $dptBretagne =
            [
                '22' => 'Côtes-d\'Armor',
                '29' => 'Finistère',
                '35' => 'Ille-et-Vilaine',
                '56'=>'Morbihan',
            ];

        $bretagne = new Region();
        $bretagne->setName('Bretagne');
        $manager->persist($bretagne);

        foreach($dptBretagne as $code=>$name)
        {
            $dptbre = new Department();
            $dptbre->setCode($code)
                ->setName($name)
                ->setRegion($bretagne);

            $manager->persist($dptbre);
        }

        $dptCentre =
            [
                '18' => 'Cher',
                '28' => 'Eure-et-Loir',
                '36' => 'Indre',
                '37' => 'Indre-et-Loire',
                '41' => 'Loir-et-Cher',
                '45' => 'Loiret',


            ];

        $centre = new Region();
        $centre->setName('Centre-Val de Loire');
        $manager->persist($centre);

        foreach($dptCentre as $code=>$name)
        {
            $dptce = new Department();
            $dptce->setCode($code)
                ->setName($name)
                ->setRegion($centre);

            $manager->persist($dptce);
        }

        $dptCorse = [
            '2A' => 'Corse-du-Sud',
            '2B' => 'Haute-Corse',
        ];

        $corse = new Region();
        $corse->setName('Corse');
        $manager->persist($corse);

        foreach($dptCorse as $code=>$name)
        {
            $dptcor = new Department();
            $dptcor->setCode($code)
                ->setName($name)
                ->setRegion($corse);

            $manager->persist($dptcor);
        }


        $dptEst =
            [
                '08' => 'Ardennes',
                '10' => 'Aube',
                '51' => 'Marne',
                '52' => 'Haute-Marne',
                '54' => 'Meurthe-et-Moselle',
                '55' => 'Meuse',
                '57' => 'Moselle',
                '67' => 'Bas-Rhin',
                '68'=> 'Haut-Rhin',
                '88'=>'Vosges',

            ];

        $est = new Region();
        $est->setName('Grand-Est');
        $manager->persist($est);

        foreach($dptEst as $code=>$name)
        {
            $dptes = new Department();
            $dptes->setCode($code)
                ->setName($name)
                ->setRegion($est);

            $manager->persist($dptes);
        }


        $dptHDF =
            [
                '02' => 'Aisne',
                '59' => 'Nord',
                '60'=>'Oise',
                '62'=> 'Pas-de-Calais',
                '80'=> 'Somme',
            ];

        $hdf = new Region();
        $hdf->setName('Hauts-de-France');
        $manager->persist($hdf);

        foreach($dptHDF as $code=>$name)
        {
            $dpthd = new Department();
            $dpthd->setCode($code)
                ->setName($name)
                ->setRegion($hdf);

            $manager->persist($dpthd);
        }


        $dptIDF = [
            '75'=> 'Paris',
            '77'=> 'Seine-et-Marne',
            '78'=>'Yvelines',
            '91'=> 'Essonne',
            '92'=> 'Hauts-de-Seine',
            '93'=> 'Seine-Saint-Denis',
            '94'=> 'Val-de-Marne',
            '95'=> 'Val d\'Oise'
        ];

        $idf = new Region();
        $idf->setName('Ile-de-France');
        $manager->persist($idf);

        foreach($dptIDF as $code=>$name)
        {
            $dptid = new Department();
            $dptid->setCode($code)
                ->setName($name)
                ->setRegion($idf);

            $manager->persist($dptid);
        }



        $dptNormandie = [
            '14' => 'Calvados',
            '27' => 'Eure',
            '50' => 'Manche',
            '61'=> 'Orne',
            '76'=> 'Seine-Maritime',
        ];

        $normandie = new Region();
        $normandie->setName('Normandie');
        $manager->persist($normandie);

        foreach($dptNormandie as $code=>$name)
        {
            $dptnor = new Department();
            $dptnor->setCode($code)
                ->setName($name)
                ->setRegion($normandie);

            $manager->persist($dptnor);
        }


        $dptNouvelleAq = [
            '16' => 'Charente',
            '17' => 'Charente-Maritime',
            '19' => 'Corrèze',
            '23' => 'Creuse',
            '24' => 'Dordogne',
            '33' => 'Gironde',
            '40' => 'Landes',
            '47' => 'Lot-et-Garonne',
            '64' => 'Pyrénées-Atlantiques',
            '79'=> 'Deux-Sèvres',
            '86'=> 'Vienne',
            '87'=> 'Haute-Vienne',


        ];

        $nouvelleaq = new Region();
        $nouvelleaq->setName('Nouvelle-Aquitaine');
        $manager->persist($nouvelleaq);

        foreach($dptNouvelleAq as $code=>$name)
        {
            $dptna = new Department();
            $dptna->setCode($code)
                ->setName($name)
                ->setRegion($nouvelleaq);

            $manager->persist($dptna);
        }


        $dptOccitanie =
            [
                '09' => 'Ariege',
                '11' => 'Aude',
                '12' => 'Aveyron',
                '30' => 'Gard',
                '31' => 'Haute-Garonne',
                '32' => 'Gers',
                '34' => 'Hérault',
                '46' => 'Lot',
                '48' => 'Lozère',
                '65' => 'Hautes-Pyrénées',
                '66'=> 'Pyrénées-Orientales',
                '81'=> 'Tarn',
                '82'=> 'Tarn-et-Garonne',
            ];

        $occitanie = new Region();
        $occitanie->setName('Occitanie');
        $manager->persist($occitanie);

        foreach($dptOccitanie as $code=>$name)
        {
            $dptoc = new Department();
            $dptoc->setCode($code)
                ->setName($name)
                ->setRegion($occitanie);

            $manager->persist($dptoc);
        }


        $dptPDLL =
            [
                '44' => 'Loire-Atlantique',
                '49' => 'Maine-et-Loire',
                '53' => 'Mayenne',
                '72'=> 'Sarthe',
                '85'=>'Vendée',


            ];

        $pdll = new Region();
        $pdll->setName('Pays de la Loire');
        $manager->persist($pdll);

        foreach($dptPDLL as $code=>$name)
        {
            $dptpd = new Department();
            $dptpd->setCode($code)
                ->setName($name)
                ->setRegion($pdll);

            $manager->persist($dptpd);
        }



        $dptPACA =
            [
                '04' => 'Alpes-de-Haute-Provence',
                '05' => 'Hautes-Alpes',
                '06' => 'Alpes-Maritimes',
                '13' => 'Bouches-du-Rhône',
                '83'=> 'Var',
                '84'=>'Vaucluse',

            ];

        $paca = new Region();
        $paca->setName('Provence-Alpes-Côte d\'Azur');
        $manager->persist($paca);

        foreach($dptPACA as $code=>$name)
        {
            $dptpa = new Department();
            $dptpa->setCode($code)
                ->setName($name)
                ->setRegion($paca);

            $manager->persist($dptpa);
        }


        $guadeloupe = new Region();
        $guadeloupe->setName('Guadeloupe');
        $manager->persist($guadeloupe);

        $guadeloupeDPT = new Department();
        $guadeloupeDPT->setCode('971')
            ->setName('Guadeloupe')
            ->setRegion($guadeloupe);
        $manager->persist($guadeloupeDPT);

        $martinique = new Region();
        $martinique->setName('Martinique');
        $manager->persist($martinique);

        $martiniqueDPT = new Department();
        $martiniqueDPT->setCode('972')
            ->setName('Martinique')
            ->setRegion($martinique);
        $manager->persist($martiniqueDPT);

        $guyane = new Region();
        $guyane->setName('Guyane');
        $manager->persist($guyane);

        $guyaneDPT = new Department();
        $guyaneDPT->setCode('973')
            ->setName('Guyane')
            ->setRegion($guyane);
        $manager->persist($guyaneDPT);

        $reu = new Region();
        $reu->setName('La Réunion');
        $manager->persist($reu);

        $reuDPT = new Department();
        $reuDPT->setCode('974')
            ->setName('La Réunion')
            ->setRegion($reu);
        $manager->persist($reuDPT);

        $mayotte = new Region();
        $mayotte->setName('Mayotte');
        $manager->persist($mayotte);

        $mayotteDPT = new Department();
        $mayotteDPT->setCode('976')
            ->setName('Mayotte')
            ->setRegion($mayotte);
        $manager->persist($mayotteDPT);



        $manager->flush();
    }
}