<?php

namespace Jariff\MemberBundle\Controller\Search;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Jariff\ProjectBundle\Controller\BaseController;
use Jariff\DocumentBundle\Document\ImportDocument;
use Jariff\DocumentBundle\Document\CacheChoice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Jariff\AdminBundle\Form\SearchFormType;
use Jariff\ProjectBundle\Util as Util;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Ajax controller.
 * Semua request dari ajax
 *
 * @Route("/member")
 */
class SearchTradeCacheChoiceAjaxController extends BaseController
{

    /**
     * @Route(
     *   "/cache-group-searching/{category}/{slug}",
     *   name     = "member_cache_group_searching"
     * )
     *
     *
     */
    public function cacheGroupSearchingAction($category, $slug)
    {

        $checkSession = $this->session()->get('cacheChoice');

        if (empty($checkSession)) {
            $todayToSession = new \DateTime('now');
            $this->session()->set('cacheChoice', (String)strtotime($todayToSession->format("YmdHis")));
            $checkSession = $this->session()->get('cacheChoice');
        }
        $data = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $this->getUser()->getId(), 'slug' => $slug, 'category' => $category));

        $username = $this->getUser()->getId();
        if (count($data) == 0) {
            $cache = new CacheChoice();
            $cache->setCategory($category);
            $cache->setHeader($this->get('request')->get('header'));
            $cache->setBody(preg_replace('/[\s]+/', ' ', $this->get('request')->get('body')));
            $cache->setSession($checkSession);
            $cache->setUserId($username);
            $cache->setPost(null);
            $cache->setSlug($slug);
            $cache->setIsCompare(0);
            $this->dm()->persist($cache);
            $this->dm()->flush();
        }

        $data = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $username, 'category' => $category));
        $check = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $username, 'category' => $category, 'is_compare' => 1));

        $key = array();
        foreach ($data as $row) {
            $key = array_merge($key, array($row->getSlug()));
        }

        $cat = ($category === 'product') ? 'exporter' : $category;
        $field = ($category === 'importer') ? 'slug_consignee_name' : 'slug_shipper_name';
        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:CompanyDetail')
            ->field($field)
            ->in($key)
            ->field('company_as')
            ->equals($cat);

        $query = $qb->getQuery();
        $searchCount = $query->execute();


        return $this->render('JariffMemberBundle:Search/CacheChoice:' . $category . '.html.twig', array('check' => $data, 'countHistory' => count($check), 'data' => $searchCount));
    }

    /**
     * @Route(
     *   "/cache-group-searching-remove/{category}/{slug}",
     *   name     = "member_cache_group_searching_remove"
     * )
     *
     *
     */
    public function cacheGroupSearchingRemoveAction($category, $slug = null)
    {

        $checkSession = $this->session()->get('cacheChoice');
        $category = strtolower($category);
        if (!empty($slug)) {
            $data = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findOneBy(array('slug' => $slug, 'user_id' => $this->user()->getId(), 'category' => $category));
            if (!empty($data)) {
                $this->dm()->remove($data);
                $this->dm()->flush();
            }
        } else {
            $data = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $this->user()->getId(), 'category' => $category));
            foreach ($data as $dt) {
                $this->dm()->remove($dt);
            }

            $this->dm()->flush();
        }

        $check = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $this->user()->getId(), 'category' => $category, 'is_compare' => 1));


        return new Response(count($check));
    }

    /**
     * @Route(
     *   "/cache-group-searching-is-compare/{category}/{slug}",
     *   name     = "member_cache_group_is_compare"
     * )
     *
     *
     */
    public function cacheGroupIsCompareAction($category, $slug = null)
    {
        $checkSession = $this->session()->get('cacheChoice');
        $category = strtolower($category);
        $username = $this->getUser()->getId();

        $check = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $username, 'category' => $category, 'is_compare' => 1));

        if (count($check) <= 5) {
            if (!empty($slug)) {
                $data = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findOneBy(array('slug' => $slug, 'user_id' => $username, 'category' => $category));

                if (!empty($data)) {
                    $data->setIsCompare(1);
                    $this->dm()->persist($data);
                    $this->dm()->flush();
                }
            }
        }

        $check = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $username, 'category' => $category, 'is_compare' => 1));


        return new Response(count($check));
    }

    /**
     * @Route(
     *   "/ajax-cache-group-searching/{category}",
     *   name     = "ajax_member_cache_group_searching",
     *   options={"expose"=true}
     * )
     *
     *
     */
    public function cacheGroupSearchingAjaxAction($category)
    {
        $username = $this->getUser()->getId();

        $data = $this->repoMongo("JariffDocumentBundle:CacheChoice")->findBy(array('user_id' => $username, 'category' => $category));

        $key = array();
        $totalDataHistory = 0;
        foreach ($data as $row) {
            $key = array_merge($key, array($row->getSlug()));

            if ($row->getIsCompare()) {
                $totalDataHistory++;
            }
        }

        $cat = ($category === 'product') ? 'exporter' : $category;
        $field = ($category === 'importer') ? 'slug_consignee_name' : 'slug_shipper_name';
        $qb = $this->dm()->createQueryBuilder('JariffDocumentBundle:CompanyDetail')
            ->field($field)
            ->in($key)
            ->field('company_as')
            ->equals($cat);

        $query = $qb->getQuery();
        $searchCount = $query->execute();

        return $this->render('JariffMemberBundle:Search/CacheChoice:' . $category . '.html.twig', array('check' => $data, 'countHistory' => $totalDataHistory, 'data' => $searchCount));
    }

}
