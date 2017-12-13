<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Post;

class PostFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $postsList = array(
            array(
                'title' => 'Phasellus eget justo pellentesque, suscipit ex at, sodales urna. Nam.',
                'content' => 'Mauris accumsan laoreet justo ut tempor. Mauris vel tincidunt felis, eleifend vestibulum nulla. Duis non eleifend lorem. Fusce vel ultricies urna, eget placerat odio. Ut nec erat tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam et velit at ipsum luctus sollicitudin. Donec libero orci, vehicula blandit mauris vitae, molestie tincidunt elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus vel dolor sed sapien eleifend condimentum. Etiam et venenatis odio, quis porttitor quam. Proin sed nulla at massa malesuada tincidunt quis sed risus. Quisque sed hendrerit nunc, quis faucibus enim. Sed enim dui, rutrum ut porta sed, venenatis ut orci. Aliquam cursus, libero at mollis facilisis, tortor neque egestas turpis, in eleifend quam tortor sit amet nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lorem odio, ornare a elit nec, efficitur lobortis nisi. Sed in imperdiet ipsum, in sodales lorem. Praesent est urna, feugiat ut quam ut, tincidunt varius neque. Fusce pharetra, elit nec fringilla condimentum, sem nisi ullamcorper lorem, eu porta mauris ex sodales odio. Etiam efficitur condimentum bibendum. Sed in dignissim diam. Nullam mollis massa et velit convallis euismod. Integer ut tincidunt ligula. Sed volutpat risus at efficitur mollis. Suspendisse cursus, ante vitae eleifend laoreet, diam eros placerat elit, bibendum lacinia nisl leo vitae velit. Aliquam facilisis elementum ultrices. Nullam mattis, orci vitae lobortis scelerisque, sem velit vulputate erat, eu feugiat odio nibh ac enim. Vestibulum porttitor luctus dictum. Praesent at dictum turpis, et ullamcorper sapien.',
                'category' => 'podroze',
                'tags' => array('Accius', 'Scrupulum', 'Nihil'),
                'author' => 'Admin',
                'createDate' => '2017-11-02 14:31:14',
                'publishedDate' => '2017-11-02 13:31:14'
            ),
            array(
                'title' => 'Sed ultricies velit nec placerat viverra. Integer.',
                'content' => 'Donec et ex urna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur vestibulum tortor et neque pulvinar, ac accumsan ipsum imperdiet. Vestibulum ac erat interdum, faucibus nisl vitae, lobortis risus. Ut pharetra diam vel lacus tincidunt ultricies. Suspendisse vel libero elit. In sollicitudin justo non ipsum aliquam, vehicula pellentesque lectus accumsan. Nulla non risus at tellus mattis aliquam. Phasellus auctor molestie tristique. Vivamus convallis diam vitae turpis volutpat, eget posuere elit sagittis. Quisque et nisi vitae lorem aliquam vestibulum a vitae enim. Phasellus tincidunt nisi id arcu feugiat, auctor molestie ante fringilla. In dui magna, tincidunt sit amet sem sed, dictum rhoncus nunc. Nunc pellentesque risus ante, sed finibus lacus finibus id. Nulla ipsum est, viverra cursus semper rutrum, condimentum eu ante. Cras ligula lorem, laoreet nec pretium vitae, venenatis vitae quam. Vestibulum cursus orci dolor, at efficitur purus scelerisque non. Integer mattis, elit porttitor iaculis molestie, dolor sapien pharetra enim, et dignissim ipsum libero ut libero. Nam quis interdum nisi, et pulvinar enim. Fusce id tincidunt massa. Aliquam laoreet finibus velit ac tincidunt. Etiam nec ex dignissim, accumsan ante sit amet, auctor eros. Aenean imperdiet purus at ullamcorper commodo. Curabitur auctor ante ut nibh accumsan auctor. Suspendisse pretium enim purus, a pulvinar ex aliquam sed. Pellentesque diam enim, tempus et hendrerit vitae, cursus nec nulla. Nulla sollicitudin urna nibh, ut rutrum justo porta in. Interdum et malesuada fames ac ante ipsum primis in faucibus. Cras at leo varius, elementum odio a, lobortis mauris. Maecenas elit arcu, molestie sed hendrerit in, rutrum at lorem. Praesent congue quis lacus eget ornare. Fusce elementum, turpis id maximus cursus, velit est eleifend purus, in tempor sem nisi vitae quam. Sed vestibulum justo eu neque dignissim venenatis. Proin sed turpis non sem pharetra euismod. Fusce tincidunt neque sem, eu faucibus lectus tempus et. Nulla at orci ac tellus ultricies vestibulum. Integer ultrices, risus eget auctor consectetur, enim nunc elementum mi, nec tempus mauris lacus eu ante. Aliquam interdum, tortor ac consectetur varius, dui magna rhoncus nisi, sit amet scelerisque urna dolor eu lorem.',
                'category' => 'ludzie',
                'tags' => array('Maximus', 'Accius', 'Reges'),
                'author' => 'Pawel',
                'createDate' => '2017-08-14 11:59:14',
                'publishedDate' => '2017-08-14 12:05:00'
            ),
            array(
                'title' => 'Nullam aliquet dignissim condimentum. Quisque porta tempor.',
                'content' => 'Curabitur ac congue ante. Donec sit amet molestie felis. Aliquam rutrum ultrices orci eu suscipit. Nulla urna tortor, fringilla nec lacinia id, viverra non lectus. Mauris elementum ornare consectetur. Pellentesque tempor lorem ex, id hendrerit mauris scelerisque vel. Maecenas lacus elit, fermentum nec scelerisque non, semper pellentesque nisi. Aliquam erat volutpat. Nulla at diam quis urna mattis tempor vel interdum massa. Pellentesque ut eros neque. Nulla magna mi, semper a erat id, auctor accumsan elit. Ut et bibendum eros, vel viverra nisl. Curabitur egestas ipsum luctus odio imperdiet, ut ultricies lacus elementum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis non est bibendum, pellentesque turpis quis, aliquam metus. Fusce ullamcorper urna tellus, quis mollis justo finibus eu. Pellentesque vestibulum, libero in ultrices scelerisque, justo nunc vulputate lacus, eget pellentesque purus tortor at massa. Vestibulum suscipit, dui nec euismod tempus, arcu leo lacinia purus, ac sodales tortor risus eget neque. Suspendisse potenti. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean tincidunt orci sed imperdiet luctus. Donec et tincidunt urna, id interdum odio. Nam venenatis, arcu ut facilisis scelerisque, eros dolor fringilla quam, non sodales eros quam tincidunt lectus. Nam lacus quam, eleifend vel molestie et, fermentum in est. Nunc et neque non massa facilisis consequat. Donec a accumsan turpis. Nullam maximus volutpat hendrerit. Etiam sagittis posuere sem sit amet varius.',
                'category' => 'ludzie',
                'tags' => array('Quod', 'Nihil', 'Praeteritis', 'Aristophanem'),
                'author' => 'Tomek',
                'createDate' => '2017-06-30 12:31:14',
                'publishedDate' => '2017-07-05 12:14:14'
            ),
            array(
                'title' => 'Proin metus augue, tincidunt in mi.',
                'content' => 'Sed convallis diam non sapien semper, ac scelerisque mauris tempus. Proin at magna justo. Quisque non metus maximus, laoreet lorem non, porta libero. Fusce nulla ipsum, fermentum imperdiet velit nec, aliquet congue sem. Vivamus tincidunt mi vel quam pellentesque convallis. Cras at euismod eros. Sed mattis vel tortor vel eleifend. Etiam accumsan, arcu sed posuere condimentum, magna nulla efficitur nulla, a vestibulum ipsum nisl vitae lacus. Nulla venenatis metus id lorem vestibulum, nec gravida mauris fermentum. Nulla facilisi. Sed et accumsan quam, malesuada scelerisque enim. Sed lobortis egestas massa, sit amet blandit nisi suscipit a. Curabitur vestibulum lorem dui, eget ultricies est aliquam sed. Etiam sed tempor ante. Ut tellus diam, pellentesque quis ex et, mattis varius justo. Duis porta lacus eu interdum fringilla. Aenean cursus elit vel pretium vehicula. Aliquam justo dolor, lobortis id ultricies vel, volutpat nec leo. Nulla non sem sit amet dolor varius lacinia. Maecenas laoreet, metus at pharetra finibus, justo leo egestas elit, a semper ipsum purus eget sapien.',
                'category' => 'samochody',
                'tags' => array('Fugiunt', 'Qua'),
                'author' => 'wasde',
                'createDate' => '2017-05-28 12:31:14',
                'publishedDate' => '2017-05-28 12:36:14'
            ),
            array(
                'title' => 'Vestibulum convallis nunc eget erat gravida, et mattis.',
                'content' => 'Donec vel fermentum lorem. Quisque rhoncus nibh sit amet diam semper, vel rhoncus magna placerat. Ut dictum vel ante a luctus. In tellus sapien, laoreet nec convallis id, finibus sit amet arcu. Ut faucibus ac diam sit amet sollicitudin. Ut non neque at enim fermentum viverra. Suspendisse tempus lectus vel diam facilisis convallis sed at orci. Quisque tincidunt semper urna, vel imperdiet est egestas eget. Integer non elit et libero consequat pulvinar. Vestibulum vehicula ultrices lorem non interdum. Donec quis sem viverra, fermentum nisl ut, consequat sapien. Aliquam malesuada elementum libero eget semper. Vivamus arcu ipsum, molestie id commodo sit amet, tristique non magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum sem leo, vehicula cursus mollis nec, suscipit nec ex. Duis vel fringilla leo, quis rutrum sem. Mauris vehicula commodo sodales. Quisque suscipit elit in pharetra iaculis. Sed ut elit at massa porta blandit quis in lacus. Aliquam ante leo, gravida id cursus a, consectetur vel purus. Etiam vehicula nibh eros, eu congue metus imperdiet sit amet. Nullam ut ante euismod, mattis neque sed, ultrices urna. Praesent tincidunt lorem tortor, eleifend elementum lacus consectetur placerat. Nam pharetra felis ac metus tincidunt dictum.',
                'category' => 'technologie',
                'tags' => array('Accius', 'Maximus', 'Reges'),
                'author' => 'Pawel',
                'createDate' => '2017-02-27 08:01:25',
                'publishedDate' => '2017-03-01 12:05:12'
            ),
            array(
                'title' => 'Donec semper nisl at interdum.',
                'content' => 'Donec sed velit ipsum. Integer sagittis mi vitae pretium aliquam. Morbi fringilla pulvinar purus, eu dapibus enim. Praesent et dolor quis nisi hendrerit congue et mollis mauris. Aenean elit nisi, porta ut justo vel, mollis venenatis ligula. Aliquam ut finibus erat. Vestibulum eros ipsum, convallis ultrices tortor a, semper consequat eros. Curabitur eu aliquet diam. Fusce tincidunt ultricies molestie. Aliquam ex ante, dignissim vitae aliquam ultrices, ullamcorper commodo sem. Sed vestibulum, enim egestas blandit pharetra, purus velit aliquam velit, a lobortis nunc risus interdum ex. Morbi at hendrerit nisi. Pellentesque sed ultricies tortor, at dignissim libero. Phasellus sit amet neque aliquet, accumsan sem sed, luctus neque. Morbi sit amet dolor orci. Nulla id posuere purus. Vivamus sit amet orci metus. Suspendisse dictum ipsum metus, eu iaculis quam condimentum sit amet. Sed tempor dolor vel sapien consectetur tincidunt. Vestibulum vulputate dui non feugiat sodales. Integer at lorem commodo, rhoncus massa vitae, auctor velit. Fusce id mattis eros. Ut dapibus placerat consectetur.',
                'category' => 'weekend',
                'tags' => array('Nihil', 'Maximus'),
                'author' => 'Pawel',
                'createDate' => '2016-12-25 18:30:47',
                'publishedDate' => null
            ),
            array(
                'title' => 'Etiam eget euismod lacus.',
                'content' => 'Nunc tristique lorem tellus, eget tristique nunc pharetra quis. Nunc molestie, erat id scelerisque lobortis, quam lacus fringilla diam, eget imperdiet augue orci sagittis felis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce diam nulla, laoreet sit amet ligula nec, tincidunt tristique urna. Vivamus bibendum placerat pulvinar. Curabitur lacus lacus, venenatis eget nisl id, fermentum facilisis metus. Sed vel mattis ex. Phasellus sit amet lorem lobortis, lacinia mi eu, ullamcorper ipsum. Integer rhoncus congue odio, quis commodo nisl maximus ut. Cras in tortor nisi. Maecenas gravida accumsan turpis in rutrum. Ut lobortis libero ut odio malesuada eleifend. Quisque maximus, elit et posuere aliquam, risus est convallis libero, quis iaculis leo nulla sed arcu. Vivamus a ante est. Nam tempor, odio laoreet mattis fringilla, dui enim aliquet purus, at ultricies erat augue vitae dui. Pellentesque lorem tortor, condimentum vitae mi vel, facilisis suscipit elit. Ut interdum est sit amet ante ultricies finibus. Aliquam finibus sapien magna, vel mattis velit venenatis in. Fusce in commodo leo, ut placerat eros. Integer sem velit, dignissim id scelerisque at, ultrices eget eros. Ut vel magna in ante faucibus varius ut at quam. In pellentesque tempus velit, vel ultricies nunc hendrerit at. Curabitur lectus elit, placerat sed arcu in, rutrum vulputate tellus. Etiam dignissim purus elementum nibh pharetra, ut aliquam orci feugiat.',
                'category' => 'podroze',
                'tags' => array('Fugiunt', 'Quid', 'Qua'),
                'author' => 'Admin',
                'createDate' => '2016-12-20 12:12:12',
                'publishedDate' => '2016-12-20 12:12:15'
            ),
            array(
                'title' => 'Nam posuere dolor eu sagittis tempus. Quisque.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui. Praesent blandit dolor. Sed non quam. In vel mi sit amet augue congue elementum. Morbi in ipsum sit amet pede facilisis laoreet. Donec lacus nunc, viverra nec.',
                'category' => 'weekend',
                'tags' => array('Reges'),
                'author' => 'Tomek',
                'createDate' => '2016-06-05 11:12:13',
                'publishedDate' => '2016-06-25 12:18:12'
            ),
            array(
                'title' => 'Vivamus non consequat purus. Phasellus nec laoreet.',
                'content' => 'Nam ornare lobortis est eget volutpat. Nunc pellentesque porta leo ut luctus. Ut congue lorem eleifend eros tincidunt blandit. Quisque finibus consequat nibh, nec sollicitudin nisi mollis vitae. Nunc posuere libero eget efficitur vestibulum. Cras scelerisque felis ac euismod pretium. Sed pulvinar diam eu laoreet vulputate. Phasellus in elit sed ex sodales tristique. Aenean faucibus porttitor purus vitae aliquet.Integer ac lacus cursus, lacinia ligula ac, egestas magna. Maecenas facilisis ante eget urna vulputate, vel hendrerit urna luctus. Etiam ac laoreet nisl. Nullam tempor, odio quis eleifend feugiat, nulla lectus rhoncus orci, ac tincidunt neque justo eu eros. Proin id quam accumsan nunc accumsan commodo iaculis non orci. Quisque a metus commodo, commodo tellus ac, egestas massa. Nunc est nibh, vulputate sed massa a, laoreet lacinia nisi. Donec eleifend nulla elementum mauris accumsan, et pellentesque nibh convallis. Nullam eget mi lectus. Suspendisse semper lorem est, nec vulputate lacus viverra in.Vivamus mollis vulputate mauris, vitae congue nisi vehicula sed. Suspendisse sed lectus vitae augue sagittis ultricies. Praesent vitae lacus neque. Aenean sed elit commodo, facilisis nunc sed, blandit metus. Integer malesuada eros sed velit tincidunt pellentesque. Cras in nulla tempor ipsum sodales finibus eget et mauris. Praesent eu convallis ipsum. Aenean consectetur vel neque eget volutpat. Nulla porta vulputate sodales. Nunc ac ante quis nibh faucibus congue euismod nec turpis.',
                'category' => 'podroze',
                'tags' => array('Quid', 'Scrupulum', 'Praeteritis', 'Aristophanem', 'Maximus'),
                'author' => 'Admin',
                'createDate' => '2016-05-20 12:12:12',
                'publishedDate' => null
            ),
        );

        foreach ($postsList as $key => $details) {
            $Post = new Post();
            $Post->setTitle($details['title'])
                ->setContent($details['content'])
                ->setAuthor($this->getReference('user_'.$details['author']))
                ->setCreateDate(new \DateTime($details['createDate']));

                if (null !== $details['publishedDate']) {
                $Post->setPublishedDate(new \DateTime($details['publishedDate']));
                }
                $Post->setCategory($this->getReference('category_'.$details['category']));
                foreach ($details['tags'] as $tagName){
                    $Post->addTag($this->getReference('tag_'.$tagName));
                }
            $manager->persist($Post);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}