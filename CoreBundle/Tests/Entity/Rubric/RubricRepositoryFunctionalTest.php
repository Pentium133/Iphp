<?php
namespace Iphp\CoreBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Application\Iphp\CoreBundle\Entity\Rubric;

class RubricRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()
                ->get('doctrine.orm.entity_manager');
    }

    public function testFillTree()
    {
        $repo  = $this->_em->getRepository('ApplicationIphpCoreBundle:Rubric');

        // $this->assertEquals(count($results), 1);


        $connection = $this->_em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('rubric__rubric', true /* whether to cascade */));

        // print get_class ($root);

        $rootRubric = new Rubric();
        $rootRubric->setTitle('Рубрики')->setPath('')->setStatus(true);
        $this->_em->persist($rootRubric);
        $this->_em->flush();



        $rubrics = array();
        for ($i = 1; $i<=3;$i++)
        {
            $rubrics[$i] = new Rubric();
            $rubrics[$i]->setTitle(str_repeat ($i,5))->setPath(str_repeat ($i,5))->setStatus(true)->setParent($rootRubric);

            $this->_em->persist($rubrics[$i]);
        }

        print "\n".'childs first save';
        $this->_em->flush();
        print "\nok;";

        $repo->clear();
        unset($rubrics);


/*
        $rubric1 = $repo->find(2);
        print $rubric1.',left:'.$rubric1->getLeft();
        $repo->persistAsLastChild( $rubric1);
        $this->_em->flush();


        print ' ==> '.$rubric1.',left:'.$rubric1->getLeft();*/

        $rubric3 = $repo->find(4);

        $this->assertEquals ($rubric3->getLeft(),6);
        $this->assertEquals ($rubric3->getRight(),7);
       //    print "\n".$rubric3.',left:'.$rubric3->getLeft();



        $rubric1 = $repo->find(2);

        $this->assertEquals ($rubric1->getLeft(),2);
        $this->assertEquals ($rubric1->getRight(),3);
        //               print ' '.$rubric1.',left:'.$rubric1->getLeft();
       // $rubric1->setParent ($repo->find(1));


       $repo->persistAsNextSiblingOf ($rubric1,$rubric3 );
        $this->_em->flush();

        $repo->clear();




    /*    $repo->persistAsFirstChild( $rubric3 );

        print $rubric3.',left:'.$rubric3->getLeft();*/

        print "\n";
        print_r ( $repo->childrenHierarchy(
               null, /* starting from root nodes */
              false, /* load all children, not only direct */
            array('decorate' => true)));
        $rubric1 = $repo->find(2);

         $this->assertEquals ($rubric1->getLeft(),6);

    }
}
