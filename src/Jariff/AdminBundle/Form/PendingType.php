<?php

namespace Jariff\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PendingType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('discount', 'integer', array())
            ->add('everythingPlan', 'checkbox', array(
                    'required' => false,
                ))
            ->add('customPlan', 'checkbox', array(
                    'required' => false,
                ))
            ->add('history', 'choice', array(
                'choices'   => array(
                    59  => '18 months = $59 per month',
                    99  => '36 months = $99 per month',
                    150 => '60 months = $150 per month',
                ),
            ))
            ->add('search', 'choice', array(
                'choices'   => array(
                    0  => '    5 (included)',
                    10  => '   25 = $10 per month',
                    20 => '   50 = $20 per month',
                    35 => 'unlimited = $35 per month',
                )
            ))
            ->add('download', 'choice', array(
                'choices'   => array(
                    0  => 'Add number of records :',
                    30 => '      1 000 = $30 per month',
                    40 => '      5 000 = $40 per month',
                    60 => '     25 000 = $60 per month',
                    70 => '    100 000 = $70 per month',
                    80 => 'unlimited = $80 per month',
                )
            ))
            ->add('bigPicture', 'choice', array(
                'choices'   => array(
                    0  => 'off',
                    30 => 'on = $30 per month',
                )
            ))


            ->add('paymentTerm', 'choice', array(
                'label' => 'Payment Frequency',
                'choices'   => array(
                    'mtm' => 'Month to Month',
                    'pif' => 'Paid in full',
                )
            ))

            ->add('month', 'choice', array(
                'label' => 'Subscription Length',
                'choices'   => array(
                     10 => '3 months: discount 10%',
                     15 => '6 months: discount 15%',
                     20 => '12 months: discount 20%',
                )
            ))
            // ->add('quarterlyFeedback')



            
            ->add('email', 'text', array(
                    'attr' => array(
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('phone', 'text', array(
                    'attr' => array(
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('salutation', 'choice', array(
                'choices'   => array(
                    'Mr.'  => 'Mr.',  
                    'Mrs.' => 'Mrs.', 
                    'Ms.'  => 'Ms.',
                ),
                    'attr' => array(
                        'style' => 'width: 54px;',
                    ),
                ))
            ->add('firstName', 'text', array(
                    'attr' => array(
                        'style'       => 'width: 200px;',
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('lastName', 'text', array(
                    'attr' => array(
                        'style'       => 'width: 180px;',
                        'class'       => 'record tooltip22',
                    ),
                ))
            // ->add('password', 'password', array(
            //         'required' => false,
            //         'attr' => array(
            //             'class'       => 'tooltip22',
            //         ),
            //     ))
            ->add('companyName', 'text', array(
                    'attr' => array(
                        'class'       => 'record tooltip22',
                    ),
                ))
            ->add('country', 'choice', array(
                'label' => 'What country do you live in?',
                'choices'   => $this->getCoutnries(),
                'preferred_choices' => array(
                    "United States"  => "United States",
                ),
                'attr' => array(
                    'class' => 'select2',
                    'style' => 'width: 75%;',
                )))
            ->add('payment', 'choice', array(
                'label' => 'Payment type',
                'choices'   => array(
                    'cc'       => 'Credit Card',  
                    'paypal'   => 'PayPal',  
                    'check'    => 'Check',  
                    'bankwire' => 'Bankwire',
                ),
                'attr' => array(
                    'style' => 'width: 200px;'
                )))
            ->add('ccType', 'choice', array(
                'label' => 'Credit card type',
                'choices'   => array(
                    'mastercard'      => 'MasterCard',          
                    'visa'             => 'Visa',                        
                    'american express' => 'American Express',
                    'discover'         => 'Discover',                
                ),
                'attr' => array(
                    'style' => 'width: 200px;'
                )))
            ->add('number', 'text', array(
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 200px;',
                        'class' => 'tooltip22',
                        'title' => 'Just type numeric input. AmEx credit card must 15 digit length. Visa, MasterCard, and Discover must 16 digit length.'
                    ),
                ))
            ->add('secureCode', 'text', array(
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 200px;',
                        'class' => 'tooltip22',
                        'title' => '<img width="250px" src="/bundles/jariffproject/frontend/images/creditcardsecurity.gif"/>'
                    ),
                ))
            ->add('expired', 'date', array(
                'required'    => false,
                'label'       => 'Expiration Date',
                'widget'      => 'choice',
                'years'       => range(date('Y'), date('Y')+12),
                'days'        => array(1),
                'empty_value' => array('year' => false, 'month' => false, 'day' => false),
            ))
            ->add('term', 'checkbox', array(
                'required' => false,
                'label' => 'Terms and Conditions',
                'mapped' => false
            ));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jariff\MemberBundle\Entity\Pending'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pending';
    }

    public function getCoutnries()
    {
        return array(
            "Afghanistan"                                  => "Afghanistan", 
            "Aland Islands"                                => "Aland Islands", 
            "Albania"                                      => "Albania", 
            "Algeria"                                      => "Algeria", 
            "American Samoa"                               => "American Samoa", 
            "Andorra"                                      => "Andorra", 
            "Angola"                                       => "Angola", 
            "Anguilla"                                     => "Anguilla", 
            "Antarctica"                                   => "Antarctica", 
            "Antigua And Barbuda"                          => "Antigua And Barbuda", 
            "Argentina"                                    => "Argentina", 
            "Armenia"                                      => "Armenia", 
            "Aruba"                                        => "Aruba", 
            "Australia"                                    => "Australia", 
            "Austria"                                      => "Austria", 
            "Azerbaijan"                                   => "Azerbaijan", 
            "Bahamas"                                      => "Bahamas", 
            "Bahrain"                                      => "Bahrain", 
            "Bangladesh"                                   => "Bangladesh", 
            "Barbados"                                     => "Barbados", 
            "Belarus"                                      => "Belarus", 
            "Belgium"                                      => "Belgium", 
            "Belize"                                       => "Belize", 
            "Benin"                                        => "Benin", 
            "Bermuda"                                      => "Bermuda", 
            "Bhutan"                                       => "Bhutan", 
            "Bolivia"                                      => "Bolivia", 
            "Bosnia and Herzegovina"                       => "Bosnia and Herzegovina", 
            "Botswana"                                     => "Botswana", 
            "Bouvet Island"                                => "Bouvet Island", 
            "Brazil"                                       => "Brazil", 
            "British Indian Ocean Territory"               => "British Indian Ocean Territory", 
            "Brunei Darussalam"                            => "Brunei Darussalam", 
            "Bulgaria"                                     => "Bulgaria", 
            "Burkina Faso"                                 => "Burkina Faso", 
            "Burundi"                                      => "Burundi", 
            "Cambodia"                                     => "Cambodia", 
            "Cameroon"                                     => "Cameroon", 
            "Canada"                                       => "Canada", 
            "Cape Verde"                                   => "Cape Verde", 
            "Cayman Islands"                               => "Cayman Islands", 
            "Central African Republic"                     => "Central African Republic", 
            "Chad"                                         => "Chad", 
            "Chile"                                        => "Chile", 
            "China"                                        => "China", 
            "Christmas Island"                             => "Christmas Island", 
            "Cocos Keeling Islands"                        => "Cocos Keeling Islands", 
            "Colombia"                                     => "Colombia", 
            "Comoros"                                      => "Comoros", 
            "Congo"                                        => "Congo", 
            "Congo the Democratic Republic of the"         => "Congo the Democratic Republic of the", 
            "Cook Islands"                                 => "Cook Islands", 
            "Costa Rica"                                   => "Costa Rica", 
            "Cote dIvoire"                                 => "Cote dIvoire", 
            "Croatia"                                      => "Croatia", 
            "Cuba"                                         => "Cuba", 
            "Curacao"                                      => "Curacao", 
            "Cyprus"                                       => "Cyprus", 
            "Czech Republic"                               => "Czech Republic", 
            "Denmark"                                      => "Denmark", 
            "Djibouti"                                     => "Djibouti", 
            "Dominica"                                     => "Dominica", 
            "Dominican Republic"                           => "Dominican Republic", 
            "East Timor"                                   => "East Timor", 
            "Ecuador"                                      => "Ecuador", 
            "Egypt"                                        => "Egypt", 
            "El Salvador"                                  => "El Salvador", 
            "Equatorial Guinea"                            => "Equatorial Guinea", 
            "Eritrea"                                      => "Eritrea", 
            "Estonia"                                      => "Estonia", 
            "Ethiopia"                                     => "Ethiopia", 
            "Falkland Islands"                             => "Falkland Islands", 
            "Faroe Islands"                                => "Faroe Islands", 
            "Fiji"                                         => "Fiji", 
            "Finland"                                      => "Finland", 
            "France"                                       => "France", 
            "French Guiana"                                => "French Guiana", 
            "French Polynesia"                             => "French Polynesia", 
            "French Southern Territories"                  => "French Southern Territories", 
            "Gabon"                                        => "Gabon", 
            "Gambia"                                       => "Gambia", 
            "Gaza Strip Administered by Israel"            => "Gaza Strip Administered by Israel", 
            "Georgia"                                      => "Georgia", 
            "Germany"                                      => "Germany", 
            "Ghana"                                        => "Ghana", 
            "Gibraltar"                                    => "Gibraltar", 
            "Greece"                                       => "Greece", 
            "Greenland"                                    => "Greenland", 
            "Grenada"                                      => "Grenada", 
            "Guadeloupe"                                   => "Guadeloupe", 
            "Guam"                                         => "Guam", 
            "Guatemala"                                    => "Guatemala", 
            "Guernsey"                                     => "Guernsey", 
            "Guinea"                                       => "Guinea", 
            "GuineaBissau"                                 => "GuineaBissau", 
            "Guyana"                                       => "Guyana", 
            "Haiti"                                        => "Haiti", 
            "Heard and Mc Donald Islands"                  => "Heard and Mc Donald Islands", 
            "Honduras"                                     => "Honduras", 
            "Hong Kong"                                    => "Hong Kong", 
            "Hungary"                                      => "Hungary", 
            "Iceland"                                      => "Iceland", 
            "India"                                        => "India", 
            "Indonesia"                                    => "Indonesia", 
            "Iran"                                         => "Iran", 
            "Iraq"                                         => "Iraq", 
            "Ireland"                                      => "Ireland", 
            "Isle of Man"                                  => "Isle of Man", 
            "Israel"                                       => "Israel", 
            "Italy"                                        => "Italy", 
            "Jamaica"                                      => "Jamaica", 
            "Japan"                                        => "Japan", 
            "Jersey"                                       => "Jersey", 
            "Jordan"                                       => "Jordan", 
            "Kazakhstan"                                   => "Kazakhstan", 
            "Kenya"                                        => "Kenya", 
            "Kiribati"                                     => "Kiribati", 
            "Kuwait"                                       => "Kuwait", 
            "Kyrgyzstan"                                   => "Kyrgyzstan", 
            "Laos"                                         => "Laos", 
            "Latvia"                                       => "Latvia", 
            "Lebanon"                                      => "Lebanon", 
            "Lesotho"                                      => "Lesotho", 
            "Liberia"                                      => "Liberia", 
            "Libya"                                        => "Libya", 
            "Liechtenstein"                                => "Liechtenstein", 
            "Lithuania"                                    => "Lithuania", 
            "Luxembourg"                                   => "Luxembourg", 
            "Macau"                                        => "Macau", 
            "Macedonia"                                    => "Macedonia", 
            "Madagascar"                                   => "Madagascar", 
            "Malawi"                                       => "Malawi", 
            "Malaysia"                                     => "Malaysia", 
            "Maldives"                                     => "Maldives", 
            "Mali"                                         => "Mali", 
            "Malta"                                        => "Malta", 
            "Marshall Islands"                             => "Marshall Islands", 
            "Martinique"                                   => "Martinique", 
            "Mauritania"                                   => "Mauritania", 
            "Mauritius"                                    => "Mauritius", 
            "Mayotte"                                      => "Mayotte", 
            "Mexico"                                       => "Mexico", 
            "Micronesia Federated States of"               => "Micronesia Federated States of", 
            "Moldova Republic of"                          => "Moldova Republic of", 
            "Monaco"                                       => "Monaco", 
            "Mongolia"                                     => "Mongolia", 
            "Montenegro"                                   => "Montenegro", 
            "Montserrat"                                   => "Montserrat", 
            "Morocco"                                      => "Morocco", 
            "Mozambique"                                   => "Mozambique", 
            "Myanmar"                                      => "Myanmar", 
            "Namibia"                                      => "Namibia", 
            "Nauru"                                        => "Nauru", 
            "Nepal"                                        => "Nepal", 
            "Netherlands"                                  => "Netherlands", 
            "Netherlands Antilles"                         => "Netherlands Antilles", 
            "New Caledonia"                                => "New Caledonia", 
            "New Zealand"                                  => "New Zealand", 
            "Nicaragua"                                    => "Nicaragua", 
            "Niger"                                        => "Niger", 
            "Nigeria"                                      => "Nigeria", 
            "Niue"                                         => "Niue", 
            "Norfolk Island"                               => "Norfolk Island", 
            "Northern Ireland"                             => "Northern Ireland", 
            "Northern Mariana Islands"                     => "Northern Mariana Islands", 
            "North Korea"                                  => "North Korea", 
            "Norway"                                       => "Norway", 
            "Oman"                                         => "Oman", 
            "Pakistan"                                     => "Pakistan", 
            "Palau"                                        => "Palau", 
            "Palestine"                                    => "Palestine", 
            "Panama"                                       => "Panama", 
            "Papua New Guinea"                             => "Papua New Guinea", 
            "Paraguay"                                     => "Paraguay", 
            "Peru"                                         => "Peru", 
            "Philippines"                                  => "Philippines", 
            "Pitcairn"                                     => "Pitcairn", 
            "Poland"                                       => "Poland", 
            "Portugal"                                     => "Portugal", 
            "Puerto Rico"                                  => "Puerto Rico", 
            "Qatar"                                        => "Qatar", 
            "Republic of Kosovo"                           => "Republic of Kosovo", 
            "Reunion"                                      => "Reunion", 
            "Romania"                                      => "Romania", 
            "Russia"                                       => "Russia", 
            "Rwanda"                                       => "Rwanda", 
            "Saint Barthelemy"                             => "Saint Barthelemy", 
            "Saint Kitts and Nevis"                        => "Saint Kitts and Nevis", 
            "Saint Lucia"                                  => "Saint Lucia", 
            "Saint Martin"                                 => "Saint Martin", 
            "Saint Vincent and the Grenadines"             => "Saint Vincent and the Grenadines", 
            "Samoa Independent"                            => "Samoa Independent", 
            "San Marino"                                   => "San Marino", 
            "Sao Tome and Principe"                        => "Sao Tome and Principe", 
            "Saudi Arabia"                                 => "Saudi Arabia", 
            "Scotland"                                     => "Scotland", 
            "Senegal"                                      => "Senegal", 
            "Serbia"                                       => "Serbia", 
            "Seychelles"                                   => "Seychelles", 
            "Sierra Leone"                                 => "Sierra Leone", 
            "Singapore"                                    => "Singapore", 
            "Sint Maarten"                                 => "Sint Maarten", 
            "Slovakia"                                     => "Slovakia", 
            "Slovenia"                                     => "Slovenia", 
            "Solomon Islands"                              => "Solomon Islands", 
            "Somalia"                                      => "Somalia", 
            "South Africa"                                 => "South Africa", 
            "South Georgia and the South Sandwich Islands" => "South Georgia and the South Sandwich Islands", 
            "South Korea"                                  => "South Korea", 
            "South Sudan"                                  => "South Sudan", 
            "Spain"                                        => "Spain", 
            "Sri Lanka"                                    => "Sri Lanka", 
            "St Helena"                                    => "St Helena", 
            "St Pierre and Miquelon"                       => "St Pierre and Miquelon", 
            "Sudan"                                        => "Sudan", 
            "Suriname"                                     => "Suriname", 
            "Svalbard and Jan Mayen Islands"               => "Svalbard and Jan Mayen Islands", 
            "Swaziland"                                    => "Swaziland", 
            "Sweden"                                       => "Sweden", 
            "Switzerland"                                  => "Switzerland", 
            "Syria"                                        => "Syria", 
            "Taiwan"                                       => "Taiwan", 
            "Tajikistan"                                   => "Tajikistan", 
            "Tanzania"                                     => "Tanzania", 
            "Thailand"                                     => "Thailand", 
            "Togo"                                         => "Togo", 
            "Tokelau"                                      => "Tokelau", 
            "Tonga"                                        => "Tonga", 
            "Trinidad and Tobago"                          => "Trinidad and Tobago", 
            "Tunisia"                                      => "Tunisia", 
            "Turkey"                                       => "Turkey", 
            "Turkmenistan"                                 => "Turkmenistan", 
            "Turks and Caicos Islands"                     => "Turks and Caicos Islands", 
            "Tuvalu"                                       => "Tuvalu", 
            "Uganda"                                       => "Uganda", 
            "Ukraine"                                      => "Ukraine", 
            "United Arab Emirates"                         => "United Arab Emirates", 
            "United Kingdom"                               => "United Kingdom", 
            "United States"                                => "United States", 
            "United States Minor Outlying Islands"         => "United States Minor Outlying Islands", 
            "Uruguay"                                      => "Uruguay", 
            "Uzbekistan"                                   => "Uzbekistan", 
            "Vanuatu"                                      => "Vanuatu", 
            "Vatican City State Holy See"                  => "Vatican City State Holy See", 
            "Venezuela"                                    => "Venezuela", 
            "Viet Nam"                                     => "Viet Nam", 
            "Virgin Islands British"                       => "Virgin Islands British", 
            "Virgin Islands US"                            => "Virgin Islands US", 
            "Wales"                                        => "Wales", 
            "Wallis and Futuna Islands"                    => "Wallis and Futuna Islands", 
            "West Bank Administered by Israel"             => "West Bank Administered by Israel", 
            "Western Sahara"                               => "Western Sahara", 
            "Yemen"                                        => "Yemen", 
            "Zambia"                                       => "Zambia", 
            "Zimbabwe"                                     => "Zimbabwe", 
        );
    }
}
