<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Tricks;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //User
        $user = new User();
        $user->setPseudo('test');
        $user->setEmail('test@fixture.fr');
        $user->setAvatar('defaultavatar.png');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'Test1234?'
            )
        );
        $user->setRoles(["ROLE_ADMIN", "ROLE_SUPER_ADMIN"]);
        $user->setEnabled(true);
        $manager->persist($user);

        //Category
        $grabs = new Category();
        $rotations = new Category();
        $flips = new Category();

        $grabs->setTitle('Grabs');
        $rotations->setTitle('rotations');
        $flips->setTitle('flips');
        
        $manager->persist($grabs);
        $manager->persist($rotations);
        $manager->persist($flips);

        $manager->flush();

        //Tricks
        $image = new Image();
        $video = new Video();

        $mute = new Trick();
        $mute->setUserId($user);
        $mute->setName("Mute");
        $mute->setDescription("Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.");
        $mute->setImage("mute1-5f9fb755744a5.jpg");
        $mute->setCreatedAt(new \DateTime('now'));
        $mute->setCategoryId($grabs);
        
        $image->setName('mute1-5fa8fa7130413.jpg');
        $video->setUrl("https://www.youtube.com/embed/jm19nEvmZgM");
        
        $mute->addImage($image);
        $mute->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $indy = new Trick();
        $indy->setUserId($user);
        $indy->setName("Indy");
        $indy->setDescription("Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.");
        $indy->setImage("default.jpg");
        $indy->setCreatedAt(new \DateTime('now'));
        $indy->setCategoryId($grabs);
        
        $image->setName('indy-5fa9264c32167.jpeg');
        $video->setUrl("https://www.youtube.com/embed/6yA3XqjTh_w");
        
        $indy->addImage($image);
        $indy->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $front = new Trick();
        $front->setUserId($user);
        $front->setName("Front flip");
        $front->setDescription("Un flip est une rotation verticale, un front flip est une  rotations en avant. Il est possible de faire plusieurs flips à la suite, et d'ajouter un grab à la rotation.");
        $front->setImage("frontflip-5fa92783237da.jpeg");
        $front->setCreatedAt(new \DateTime('now'));
        $front->setCategoryId($rotations);

        $image->setName('frontflip1-5fa9278324f07.jpeg');
        $video->setUrl("https://www.youtube.com/embed/qvnsjVJCbA0");

        $front->addImage($image);
        $front->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $stalefish = new Trick();
        $stalefish->setUserId($user);
        $stalefish->setName("Stalefish");
        $stalefish->setDescription("Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.");
        $stalefish->setImage("stalefish-2.jpg");
        $stalefish->setCreatedAt(new \DateTime('now'));
        $stalefish->setCategoryId($grabs);

        $image->setName('stalefish.jpg');
        $video->setUrl("https://www.youtube.com/embed/OparOr70iu0");

        $stalefish->addImage($image);
        $stalefish->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $tailGrab = new Trick();
        $tailGrab->setUserId($user);
        $tailGrab->setName("Tail Grab");
        $tailGrab->setDescription("Saisie de la partie arrière de la planche, avec la main arrière.");
        $tailGrab->setImage("tailGrab-2.jpg");
        $tailGrab->setCreatedAt(new \DateTime('now'));
        $tailGrab->setCategoryId($grabs);

        $image->setName('tailGrab-1.jpg');
        $video->setUrl("https://www.youtube.com/embed/_Qq-YoXwNQY");

        $tailGrab->addImage($image);
        $tailGrab->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $noseGrab = new Trick();
        $noseGrab->setUserId($user);
        $noseGrab->setName("Nose Grab");
        $noseGrab->setDescription("Saisie de la partie avant de la planche, avec la main avant.");
        $noseGrab->setImage("default.jpg");
        $noseGrab->setCreatedAt(new \DateTime('now'));
        $noseGrab->setCategoryId($grabs);

        $image->setName('noseGrab.jpg');
        $video->setUrl("https://www.youtube.com/embed/OparOr70iu0");

        $noseGrab->addImage($image);
        $noseGrab->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $r180 = new Trick();
        $r180->setUserId($user);
        $r180->setName("180");
        $r180->setDescription("Rotation de la planche à 180°.");
        $r180->setImage("180.jpg");
        $r180->setCreatedAt(new \DateTime('now'));
        $r180->setCategoryId($rotations);

        $image->setName('180-1.jpg');
        $video->setUrl("https://www.youtube.com/embed/OparOr70iu0");

        $r180->addImage($image);
        $r180->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $r360 = new Trick();
        $r360->setUserId($user);
        $r360->setName("360");
        $r360->setDescription("Rotation de la planche à 360°.");
        $r360->setImage("360.jpg");
        $r360->setCreatedAt(new \DateTime('now'));
        $r360->setCategoryId($rotations);

        $image->setName('360-2.jpg');
        $video->setUrl("https://www.youtube.com/embed/OparOr70iu0");

        $r180->addImage($image);
        $r180->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $r540 = new Trick();
        $r540->setUserId($user);
        $r540->setName("540");
        $r540->setDescription("Rotation de la planche à 540°.");
        $r540->setImage("540.jpg");
        $r540->setCreatedAt(new \DateTime('now'));
        $r540->setCategoryId($rotations);

        $image->setName('540-2.jpg');
        $video->setUrl("https://www.youtube.com/embed/OparOr70iu0");

        $r540->addImage($image);
        $r540->addVideo($video);
        //
        $image = new Image();
        $video = new Video();

        $r720 = new Trick();
        $r720->setUserId($user);
        $r720->setName("720");
        $r720->setDescription("Rotation de la planche à 720°.");
        $r720->setImage('720.jpg');
        $r720->setCreatedAt(new \DateTime('now'));
        $r720->setCategoryId($rotations);

        $image->setName("720-1.jpg");
        $video->setUrl("https://www.youtube.com/embed/OparOr70iu0");

        $r720->addVideo($video);
        $r720->addImage($image);

        $manager->persist($mute);
        $manager->persist($indy);
        $manager->persist($front);
        $manager->persist($stalefish);
        $manager->persist($tailGrab);
        $manager->persist($noseGrab);
        $manager->persist($r180);
        $manager->persist($r360);
        $manager->persist($r540);
        $manager->persist($r720);

        $manager->flush();
    }
}
