<?php
namespace AppBundle\Twig;

use AppBundle\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Registry as doctrine;
use Symfony\Component\Translation\Translator;
class AppExtension extends \Twig_Extension
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @var Translator
     */
    private $translator;


    public function __construct($doctrine, $translator)
    {
        $this->doctrine = $doctrine;
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('print_main_menu',
                array($this, 'printMainMenu'),
                array(
                    'needs_environment' => true,
                    'is_safe' => ['html']
                )),
            new \Twig_SimpleFunction('print_last_post',
                array($this, 'printLastPost'),
                array(
                    'needs_environment' => true,
                    'is_safe' => ['html']
                )),
            new \Twig_SimpleFunction('print_archive',
                array($this, 'printArchive'),
                array(
                    'needs_environment' => true,
                    'is_safe' => ['html']
                )),
            new \Twig_SimpleFunction('print_account_menu',
                array($this, 'printAccountMenu'),
                array(
                    'needs_environment' => true,
                    'is_safe' => ['html']
                )),
            new \Twig_SimpleFunction('print_admin_menu',
                array($this, 'printAdminMenu'),
                array(
                    'needs_environment' => true,
                    'is_safe' => ['html']
                )),
        );
    }
    public function printMainMenu(\Twig_Environment $environment){
        $mainMenu = array(
            $this->translator->trans('menu.front.main',[],'controller') => 'blog_index',
            $this->translator->trans('menu.front.posts',[],'controller') => 'blog_news',
            $this->translator->trans('menu.front.about',[],'controller') => 'blog_about',
            $this->translator->trans('menu.front.contact',[],'controller') => 'blog_contact'
        );
        return $environment->render(':Template:mainMenu.html.twig', array(
            'mainMenu' => $mainMenu
        ));
    }
    public function printLastPost(\Twig_Environment $environment, $limit = 3){
        $repository = $this->doctrine->getRepository(Post::class);
        $lastpost = $repository->getLastPublished($limit);

        return $environment->render(':Template:lastPublished.html.twig', array(
            'lastpost' => $lastpost
        ));
    }
    public function printArchive(\Twig_Environment $environment){
        $s = new \DateTime('1 years ago');
        $e = new \DateTime();
        $start = date_modify($s, 'first day of this month');
        $end = date_modify($e, 'first day of this month');
        $interval = new \DateInterval('P1M');
        $period   = new \DatePeriod($start, $interval, $end);
        $months = array();
        foreach ($period as $dt) {
            $months[$dt->format('Y-m-d')] = $dt->format('Y-m');
        }
        return $environment->render(':Template:archive.html.twig', array(
            'months' => $months
        ));
    }

    public function printAccountMenu(\Twig_Environment $environment ){
        $accountMenu = array(
            $this->translator->trans('account.settings.menu.account',[],'controller') => 'account_settings',
            $this->translator->trans('account.settings.menu.change_password',[],'controller') => 'account_change_password',
            $this->translator->trans('account.settings.menu.change_email',[],'controller') => 'account_change_email'
        );
        return $environment->render(':Template:accountMenu.html.twig', array(
            'accountMenu' => $accountMenu
        ));
    }

    public function printAdminMenu(\Twig_Environment $environment){
        $menu = array(
            $this->translator->trans('menu.admin.main',[],'controller') => 'admin_panel',
            $this->translator->trans('menu.admin.posts',[],'controller') => 'admin_post',
            $this->translator->trans('menu.admin.categories',[],'controller') => 'admin_category',
            $this->translator->trans('menu.admin.tags',[],'controller') => 'admin_tag',
            $this->translator->trans('menu.admin.users',[],'controller') => 'admin_user'
        );

        return $environment->render(':Template:AdminMenu.html.twig', array(
            'adminMenu' => $menu
        ));
    }

}