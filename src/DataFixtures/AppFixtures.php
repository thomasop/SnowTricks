<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Trick;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }

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
        $image1 = new Image();
        $video1 = new Video();

        $mute = new Trick();
        $mute->setUser($user);
        $mute->setName("Mute");
        $mute->setDescription("Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.");
        $mute->setPicture("mute.jpeg");
        $mute->setCreatedAt(new \DateTime('now'));
        $mute->setCategoryId($grabs);
        $mute->setSlug("mute");
        $image1->setName('mute2.jpeg');
        $video1->setUrl("https://www.youtube.com/embed/6yA3XqjTh_w");
        $image1->setTrickId($mute);
        $video1->setTrickId($mute);
        //
        
        $image2 = new Image();
        $video2 = new Video();

        $indy = new Trick();
        $indy->setUser($user);
        $indy->setName("Indy");
        $indy->setDescription("Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.");
        $indy->setPicture("Indy.jpeg");
        $indy->setCreatedAt(new \DateTime('now'));
        $indy->setCategoryId($grabs);
        $indy->setSlug("indy");
        $image2->setName('Ind2.jpeg');
        $video2->setUrl("https://www.youtube.com/embed/6yA3XqjTh_w");
        $image2->setTrickId($indy);
        $video2->setTrickId($indy);
        
        //
        $image3 = new Image();
        $video3 = new Video();

        $front = new Trick();
        $front->setUser($user);
        $front->setName("Front flip");
        $front->setDescription("Un flip est une rotation verticale, un front flip est une  rotations en avant. Il est possible de faire plusieurs flips à la suite, et d'ajouter un grab à la rotation.");
        $front->setPicture("frontflip.jpeg");
        $front->setCreatedAt(new \DateTime('now'));
        $front->setCategoryId($rotations);
        $front->setSlug("front-flip");
        $image3->setName('frontflip2.jpeg');
        $video3->setUrl("https://www.youtube.com/embed/qvnsjVJCbA0");
        $image3->setTrickId($front);
        $video3->setTrickId($front);

        //
        $image4 = new Image();
        $video4 = new Video();

        $stalefish = new Trick();
        $stalefish->setUser($user);
        $stalefish->setName("Stalefish");
        $stalefish->setDescription("Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.");
        $stalefish->setPicture("Stalefish2.jpeg");
        $stalefish->setCreatedAt(new \DateTime('now'));
        $stalefish->setCategoryId($grabs);
        $stalefish->setSlug("stalefish");
        $image4->setName('Stalefish.jpeg');
        $video4->setUrl("https://www.youtube.com/embed/jm19nEvmZgM");
        $image4->setTrickId($stalefish);
        $video4->setTrickId($stalefish);

        //
        $image5 = new Image();
        $video5 = new Video();

        $tailGrab = new Trick();
        $tailGrab->setUser($user);
        $tailGrab->setName("Tail Grab");
        $tailGrab->setDescription("Saisie de la partie arrière de la planche, avec la main arrière.");
        $tailGrab->setPicture("default.jpg");
        $tailGrab->setCreatedAt(new \DateTime('now'));
        $tailGrab->setCategoryId($grabs);
        $tailGrab->setSlug("tail-grab");
        $image5->setName('backflips.jpeg');
        $video5->setUrl("https://www.youtube.com/embed/M5NTCfdObfs");
        $image5->setTrickId($tailGrab);
        $video5->setTrickId($tailGrab);

        //
        $image6 = new Image();
        $video6 = new Video();

        $noseGrab = new Trick();
        $noseGrab->setUser($user);
        $noseGrab->setName("Nose Grab");
        $noseGrab->setDescription("Saisie de la partie avant de la planche, avec la main avant.");
        $noseGrab->setPicture("default.jpg");
        $noseGrab->setCreatedAt(new \DateTime('now'));
        $noseGrab->setCategoryId($grabs);
        $noseGrab->setSlug("nose-grab");
        $image6->setName('noseGrab.jpeg');
        $video6->setUrl("https://www.youtube.com/embed/KqSi94FT7EE");
        $image6->setTrickId($noseGrab);
        $video6->setTrickId($noseGrab);

        //
        $image7 = new Image();
        $video7 = new Video();

        $r180 = new Trick();
        $r180->setUser($user);
        $r180->setName("180");
        $r180->setDescription("Rotation de la planche à 180°.");
        $r180->setPicture("180.jpeg");
        $r180->setCreatedAt(new \DateTime('now'));
        $r180->setCategoryId($rotations);
        $r180->setSlug("180");
        $image7->setName('180-1.jpeg');
        $video7->setUrl("https://www.youtube.com/embed/HRNXjMBakwM");
        $image7->setTrickId($r180);
        $video7->setTrickId($r180);

        //
        $image8 = new Image();
        $video8 = new Video();

        $r360 = new Trick();
        $r360->setUser($user);
        $r360->setName("360");
        $r360->setDescription("Rotation de la planche à 360°.");
        $r360->setPicture("default.jpg");
        $r360->setCreatedAt(new \DateTime('now'));
        $r360->setCategoryId($rotations);
        $r360->setSlug("360");
        $image8->setName('Indy3.jpeg');
        $video8->setUrl("https://www.youtube.com/embed/GS9MMT_bNn8");
        $image8->setTrickId($r360);
        $video8->setTrickId($r360);

        //
        $image9 = new Image();
        $video9 = new Video();

        $r540 = new Trick();
        $r540->setUser($user);
        $r540->setName("540");
        $r540->setDescription("Rotation de la planche à 540°.");
        $r540->setPicture("default.jpg");
        $r540->setCreatedAt(new \DateTime('now'));
        $r540->setCategoryId($rotations);
        $r540->setSlug("540");
        $image9->setName('frontflip3.jpeg');
        $video9->setUrl("https://www.youtube.com/embed/_hJX9HrdkeA");
        $image9->setTrickId($r540);
        $video9->setTrickId($r540);

        //
        $image10 = new Image();
        $video10 = new Video();

        $r720 = new Trick();
        $r720->setUser($user);
        $r720->setName("720");
        $r720->setDescription("Rotation de la planche à 720°.");
        $r720->setPicture('default.jpg');
        $r720->setCreatedAt(new \DateTime('now'));
        $r720->setCategoryId($rotations);
        $r720->setSlug("720");
        $image10->setName("backflips4.jpeg");
        $video10->setUrl("https://www.youtube.com/embed/4JfBfQpG77o");
        $image10->setTrickId($r720);
        $video10->setTrickId($r720);
        
        //
        $manager->persist($mute);
        $manager->persist($image1);
        $manager->persist($video1);
        
        $manager->persist($indy);
        $manager->persist($image2);
        $manager->persist($video2);

        $manager->persist($front);
        $manager->persist($image3);
        $manager->persist($video3);

        $manager->persist($stalefish);
        $manager->persist($image4);
        $manager->persist($video4);

        $manager->persist($tailGrab);
        $manager->persist($image5);
        $manager->persist($video5);

        $manager->persist($noseGrab);
        $manager->persist($image6);
        $manager->persist($video6);

        $manager->persist($r180);
        $manager->persist($image7);
        $manager->persist($video7);

        $manager->persist($r360);
        $manager->persist($image8);
        $manager->persist($video8);

        $manager->persist($r540);
        $manager->persist($image9);
        $manager->persist($video9);

        $manager->persist($r720);
        $manager->persist($image10);
        $manager->persist($video10);

        $manager->flush();
    }
}
