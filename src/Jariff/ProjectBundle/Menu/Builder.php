<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\ProjectBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\Iterator\RecursiveItemIterator;

class Builder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;

    }

    private $event_dispatcher;

    public function setEd($event_dispatcher)
    {
        $this->event_dispatcher = $event_dispatcher;
    }

    private $sc;

    public function setSc($sc)
    {
        $this->sc = $sc;
    }

    //sebenarnya entity manager belum dibutuhkan di menu

    private $em;

    public function setEm($em)
    {
        $this->em = $em;
    }

    private $session;

    public function setSession($session)
    {
        $this->session = $session;
    }

    // Attribute data-icon diisi nama file gambar yang wajib diletakkan di folder jariffproject/admin/img/icons/stuttgart-icon-pack/32x32/

    public function createSidebarMenu(Request $request)
    {
        $menu = $this->factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'id' => 'nav',
                    'class' => 'accordion-group'
                )
            )
        );


        if (true === $this->sc->isGranted('ROLE_SUPER_ADMIN')) {

            $menu->addChild("Dashboard", array("route" => 'dashboard_super_admin'))->setAttributes(array('data-icon' => 'home.png'))->setExtra('translation_domain', 'JariffProjectBundle');

            $menu->addChild("Monitoring", array("uri" => 'Monitoring'))->setAttributes(array('data-icon' => 'full-time.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Monitoring']->addChild("Overview", array("route" => 'super_admin_monitoring_overview'))->setLinkAttributes(array('class' => 'ajax'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Inbound", array("route" => 'super_admin_inbound_index'))->setAttributes(array('data-icon' => 'folder.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Leads", array("route" => 'super_admin_lead_index'))->setAttributes(array('data-icon' => 'library.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Member", array("route" => 'super_admin_member_index'))->setAttributes(array('data-icon' => 'address.png'))->setExtra('translation_domain', 'JariffProjectBundle');
        } else {
            $menu->addChild("Dashboard", array("route" => 'dashboard_admin'))->setAttributes(array('data-icon' => 'home.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Inbound", array("route" => 'admin_inbound_index'))->setAttributes(array('data-icon' => 'folder.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Inbound", array("uri" => 'Inbound'))->setAttributes(array('data-icon' => 'folder.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu['Inbound']->addChild("Index", array("route" => 'admin_inbound_index'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Leads", array("route" => 'admin_lead_index'))->setAttributes(array('data-icon' => 'library.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Leads", array("uri" => 'Leads'))->setAttributes(array('data-icon' => 'flag.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu['Leads']->addChild("Index", array("route" => 'admin_lead_index'))->setLinkAttributes(array('class' => 'ajax'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Member", array("route" => 'admin_member_index'))->setAttributes(array('data-icon' => 'address.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Member", array("uri" => 'Member'))->setAttributes(array('data-icon' => 'address.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu['Member']->addChild("Index", array("route" => 'admin_member_index'))->setLinkAttributes(array('class' => 'ajax'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu['Clients']->addChild("Pending Clients", array("route" => 'admin_pending_index'))->setLinkAttributes(array('class' => 'ajax'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu['Clients']->addChild("Payment", array("route" => 'admin_payment_index'))->setLinkAttributes(array('class' => 'ajax'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Outbound", array("uri" => 'Outbound'))->setAttributes(array('data-icon' => 'email.png'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu['Outbound']->addChild("Pending", array("route" => 'searching'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu['Outbound']->addChild("Active", array("route" => 'searching'))->setExtra('translation_domain', 'JariffProjectBundle');
        }

        return $menu;
    }

    // create menu frontend
    public function createMenuFrontend(Request $request)
    {
        $menu = $this->factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'id' => 'navigation',
                )
            )
        );


        if (false === $this->sc->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->addChild("Home", array("route" => 'dashboard'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Tour", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Tour']->addChild("Trade Datasets", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Tour']->addChild("Benefits", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Tour']->addChild("Videos", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Tour']->addChild("Screenshots", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Tour']->addChild("Presentation", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Demo Program", array("route" => 'demo_search'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Plan & Pricing", array("route" => 'signup'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("How We Help?", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Buyers", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Suppliers", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Logistics", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Researchers", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Attorneys", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Hedge Funds", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Investors", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Government Agencies", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Anti-Counterfeiting Groups", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['How We Help?']->addChild("Investigators", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Contact Us", array("route" => 'contact_ticket_inbound_form'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Login", array("route" => 'member_login'))->setExtra('translation_domain', 'JariffProjectBundle');

            // $menu->addChild("Home", array("route" => 'dashboard'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Tour", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Demo Program", array("route" => 'demo_search'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Plan & Pricing", array("route" => 'signup'))->setExtra('translation_domain', 'JariffProjectBundle');
            // $menu->addChild("Login", array("route" => 'member_login'))->setExtra('translation_domain', 'JariffProjectBundle');
        } else {
            $menu->addChild("Dashboard", array("route" => 'dashboard_member'))->setAttributes(array('class' => 'j-tour-dashboard'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Search", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop j-tour-groups'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Search']->addChild("Search Global", array("route" => 'member_search_global'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Search']->addChild("Search Shipments", array("route" => 'member_search_shipments'))->setExtra('translation_domain', 'JariffProjectBundle');

            $menu->addChild("Groups", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop j-tour-groups'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Groups']->addChild("Importer", array("route" => 'member_group_importer'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Groups']->addChild("Exporter", array("route" => 'member_group_exporter'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Groups']->addChild("Product", array("route" => 'member_group_product'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Stats", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop j-tour-stats'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Stats']->addChild("Search Alerts", array("route" => 'member_search_alerts'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Stats']->addChild("Search History", array("route" => 'member_search_history'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Stats']->addChild("Download Global", array("route" => 'member_search_download_global'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Stats']->addChild("Download Shipments", array("route" => 'member_search_download_shipments'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu->addChild("Account", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Account']->addChild("Support", array("route" => 'support_ticket_inbound_form'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Account']->addChild("Setting Searching", array("route" => 'membersetting'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Account']->addChild("Change Password", array("route" => 'member_change_password'))->setExtra('translation_domain', 'JariffProjectBundle');
            $menu['Account']->addChild("Logout", array("route" => 'member_logout'))->setExtra('translation_domain', 'JariffProjectBundle');
        }
        // $menu->addChild("Show in Column", array("route" => 'search_trade'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu->addChild("Global Search", array("route" => 'global_search'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu->addChild("For Suppliers", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu->addChild("For Buyers", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Buyers']->addChild("How We Have", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Buyers']->addChild("Case Studies", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Buyers']->addChild("Tour", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Buyers']->addChild("Plan & Pricing", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Buyers']->addChild("Clients", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Buyers']->addChild("Post In Buyers Inquiry", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Suppliers']->addChild("How We Have", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Suppliers']->addChild("Benefits", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Suppliers']->addChild("Plan & Pricing", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Suppliers']->addChild("Create Your Profile", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['For Suppliers']->addChild("Buyers Inquiries", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu->addChild("About Stradetegy", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['About Stradetegy']->addChild("What's New ?", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['About Stradetegy']->addChild("Intelligence Platform", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['About Stradetegy']->addChild("Technology", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['About Stradetegy']->addChild("Multiple Data Sources", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['About Stradetegy']->addChild("Board of Directors", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu['About Stradetegy']->addChild("Press", array("uri" => '#collapse-ui-elements'))->setExtra('translation_domain', 'JariffProjectBundle');
        // $menu->addChild("Contact", array("uri" => 'javascript:void(0);'))->setAttributes(array('class' => ''))->setExtra('translation_domain', 'JariffProjectBundle');

        return $menu;
    }

    // create menu frontend
    public function createMenuMemberSearch(Request $request)
    {
        $menu = $this->factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'class' => 'nav nav-tabs pull-right',
                    'role' => 'tablist'
                )
            )
        );


        $menu->addChild("New Search", array("route" => 'member_search_shipments'))->setAttributes(array('class' => ''))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("Search History", array("route" => 'member_search_history'))->setAttributes(array('class' => 'drop j-tour-groups'))->setExtra('translation_domain', 'JariffProjectBundle');
//        $menu->addChild("Favorites", array("route" => 'member_favourites'))->setAttributes(array('class' => 'drop j-tour-groups'))->setExtra('translation_domain', 'JariffProjectBundle');

        $menu->addChild("My Saved Searches", array("route" => 'member_search_save_search'))->setAttributes(array('class' => 'drop j-tour-groups'))->setExtra('translation_domain', 'JariffProjectBundle');
//        $menu->addChild("Alerts", array("route" => 'member_alerts_save_search'))->setAttributes(array('class' => 'drop j-tour-stats'))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("The Big Picture", array("route" => 'member_search_big_picture'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("Download/Export History", array("route" => 'member_search_download'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');

        return $menu;
    }

    // create menu frontend
    public function createMenuMemberAccount(Request $request)
    {
        $menu = $this->factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'class' => 'nav nav-tabs pull-right',
                    'role' => 'tablist'
                )
            )
        );


        $menu->addChild("Search Setting", array("route" => 'membersetting'))->setAttributes(array('class' => 'drop j-tour-groups'))->setExtra('translation_domain', 'JariffProjectBundle');

        $menu->addChild("Change Password", array("route" => 'member_change_password'))->setAttributes(array('class' => 'drop j-tour-groups'))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("Edit Profile", array("route" => 'profile_edit'))->setAttributes(array('class' => 'drop j-tour-stats'))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("My Subscription", array("route" => 'member_addons_setting'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');

        return $menu;
    }

    public function createMenuMemberCs(Request $request)
    {
        $menu = $this->factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'class' => 'nav nav-tabs pull-right',
                    'role' => 'tablist'
                )
            )
        );


        $menu->addChild("Support", array("route" => 'support_ticket_inbound_form'))->setAttributes(array('class' => ''))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("Glossary of Terms", array("route" => 'member_setting_glossary_terms'))->setAttributes(array('class' => ''))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("SCAC Codes", array("route" => 'member_setting_scac_codes'))->setAttributes(array('class' => ''))->setExtra('translation_domain', 'JariffProjectBundle');
        $menu->addChild("Cancel Request", array("route" => 'member_cancel_request'))->setAttributes(array('class' => 'drop'))->setExtra('translation_domain', 'JariffProjectBundle');

        return $menu;
    }

}
