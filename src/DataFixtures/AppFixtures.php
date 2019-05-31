<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Entity\Parameter;
use App\Entity\Producer;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductParameter;
use App\Entity\ProductVariant;
use App\Entity\Role;
use App\Entity\Store;
use App\Entity\User;
use App\Entity\UserSetting;
use App\Entity\Variant;
use App\Repository\AddressRepository;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\ParameterRepository;
use App\Repository\ProducerRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductParameterRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\RoleRepository;
use App\Repository\SizeRepository;
use App\Repository\StoreRepository;
use App\Repository\UserRepository;
use App\Repository\UserSettingRepository;
use App\Repository\VariantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// COMMAND: sf doctrine:fixtures:load
class AppFixtures extends Fixture
{
    private $addressRepository;
    private $categoryRepository;
    private $cityRepository;
    private $countryRepository;
    private $orderRepository;
    private $orderStatusRepository;
    private $parameterRepository;
    private $producerRepository;
    private $productImageRepository;
    private $productParameterRepository;
    private $productRepository;
    private $productVariantRepository;
    private $roleRepository;
//    private $sizeRepository;
    private $storeRepository;
    private $userRepository;
    private $userSettingRepository;
    private $variantRepository;

    public function __construct(
        AddressRepository $addressRepository,
        CategoryRepository $categoryRepository,
        CityRepository $cityRepository,
        CountryRepository $countryRepository,
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        ParameterRepository $parameterRepository,
        ProducerRepository $producerRepository,
        ProductImageRepository $productImageRepository,
        ProductParameterRepository $productParameterRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        RoleRepository $roleRepository,
//        SizeRepository $sizeRepository,
        StoreRepository $storeRepository,
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository,
        VariantRepository $variantRepository
    ) {
        $this->addressRepository = $addressRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->parameterRepository = $parameterRepository;
        $this->producerRepository = $producerRepository;
        $this->productImageRepository = $productImageRepository;
        $this->productParameterRepository = $productParameterRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->roleRepository = $roleRepository;
//        $this->sizeRepository = $sizeRepository;
        $this->storeRepository = $storeRepository;
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
        $this->variantRepository = $variantRepository;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadRoles();

        $this->loadUsers();

        $this->loadUserSettings();

        $this->loadCountries();

        $this->loadCities();

        $this->loadProducers();
        $this->loadStores();

        $this->loadOrders();

        $this->loadAddresses();

        $this->loadCategories();

        $this->loadProducts();

        $this->loadParameters();

        $this->loadProductParameters();

        $this->loadProductImages();

        $this->loadVariants();

        $this->loadProductVariants();

        $this->loadOrderStatuses();
    }

    private function loadRoles()
    {
        $roleData = [
            ['Super Administrator', 'ROLE_SUPER_ADMIN'],
            ['Administrator', 'ROLE_ADMIN'],
            ['Producer', 'ROLE_PRODUCER'],
            ['Customer', 'ROLE_CUSTOMER'],
            ['User', 'ROLE_USER'],
            ['Guest', 'ROLE_GUEST']
        ];

        foreach ($roleData as $rd) {
            $role = new Role();
            $role->setName($rd[0]);
            $role->setCode($rd[1]);
            $this->roleRepository->createEntity($role);
        }
    }

    private function loadUsers()
    {
        $userData = [
            ['admin', 'admin@admin.admin', '$argon2i$v=19$m=1024,t=16,p=2$WkRnMTAwU25vWGRUaWJ3Yw$Wws1yFIRpI1UP3sAUnTiKWSWnO6GWwEktswJO0BeuQA', 1],
            ['admin123', 'admin@ad.min', '$2y$13$q43l5g4fan65xCr0dkTxpe71Z7PQqqYatz8zYGWPbRGOCiyh2mQIC', 2],
            ['producer', 'prod@prod.prod', '$argon2i$v=19$m=1024,t=16,p=2$ZW5wem5oMFd1a2tYVlVxUg$NYvqhwv5s787f3yzAJ0BA7M+rHcQF+FrOFHloFTCG2U', 3],
            ['customer', 'customer@customer.customer', '$2y$13$t5mD8ZZYbb0Zje9DgyKtV.vthmphXjMw5N//1IT/lfuzzB69ifFBK', 4],
            ['user', 'user@used.user', '$argon2i$v=19$m=1024,t=16,p=2$d2lGTmgxWTg3QVlJSTY4Zw$qGqrYwn8FexTo3Hu7prerL5Q1GgNUByXBADUBHpSQ0M', 5],
            ['guest', 'guest@guest.guest', '$2y$13$gd0WItzUO2MGRzz0posdVeZz.K.518ecBdWwg5US24GvITcAz6Xm6', 6],
        ];

        foreach ($userData as $ud) {
            $user = new User();
            $user->setLogin($ud[0]);
            $user->setEmail($ud[1]);
            $user->setPasswordHash($ud[2]);
            $user->setRole($this->roleRepository->findOneBy(['id' => $ud[3]]));
            $this->userRepository->createEntity($user);
        }
    }

    private function loadUserSettings()
    {
        $userSettingData = [
            [1, 'de', 1],
            [2, 'pl', 0],
            [3, 'fr', 0],
            [4, 'en', 1],
            [5, 'ca', 1],
        ];

        foreach ($userSettingData as $usd) {
            $userSetting = new UserSetting();
            $userSetting->setUser($this->userRepository->findOneBy(['id' => $usd[0]]))
                ->setLocale($usd[1])
                ->setNewsletter($usd[2]);
            $this->userSettingRepository->createEntity($userSetting);
        }
    }

    private function loadCountries()
    {
        $countryData = [
            ['Poland', 'PL'],
            ['Germany', 'DE'],
            ['France', 'FR'],
            ['Canada', 'CA'],
            ['United States of America', 'US'],
            ['Netherlands', 'NL'],
            ['Belgium', 'BE'],
            ['Sri Lanka', 'LK'],
            ['United Kingdom', 'GB'],
            ['Japan', 'JP'],
        ];

        foreach ($countryData as $cd) {
            $country = new Country();
            $country->setName($cd[0]);
            $country->setSymbol($cd[1]);
            $this->countryRepository->createEntity($country);
        }
    }

    private function loadCities()
    {
        $cityData = [
            ['Warsaw', 1],
            ['Lodz', 1],
            ['Berlin', 2],
            ['New York', 5],
            ['Ottawa', 4],
            ['Springdale', 5],
            ['Burgoberbach', 2],
            ['Sri Jayawardenepura Kotte', 8],
            ['Llanfair­pwllgwyngyll­gogery­chwyrn­drobwll­llan­tysilio­gogo­goch', 9],
            ['Essendon', 9],
            ['Almelo', 6],
            ['San Diego', 5],
            ['Garvan', 9],
            ['Annemasse', 3],
            ['Toronto', 4],
        ];

        foreach ($cityData as $cd) {
            $city = new City();
            $city->setName($cd[0]);
            $city->setCountry($this->countryRepository->findOneBy(['id' => $cd[1]]));
            $this->cityRepository->createEntity($city);
        }
    }

    private function loadProducers()
    {
        $producerData = [
            [
                'Opticomp', 'Opticomp Clothing', null, null,
                'US773365342', 5, 12, 'CA 92121', '331 Hamill Avenue', 'LarrySLowell@armyspy.com',
                '858-401-5106', 4
            ],
            [
                'Omni', 'Omni Architectural Designs', 'Christopher', 'Harris',
                'GB028338520', 9, 13, 'PH33 3AF', '50 Old Chapel Road', 'RileyCraig@teleworm.us',
                '078 2966 5839', 3
            ],
            [
                'Asiatic', 'Asiatic Solutions', null, null,
                'FR69210542996', 3, 14, '74100', '65, Avenue De Marlioz', 'BrigittePatry@teleworm.us',
                '04.29.54.23.82', 2
            ]
        ];

        foreach ($producerData as $pd) {
            $producer = new Producer();
            $producer->setShortName($pd[0]);
            $producer->setFullName($pd[1]);
            $producer->setFirstName($pd[2]);
            $producer->setLastName($pd[3]);
            $producer->setVat($pd[4]);
            $producer->setCountry($this->countryRepository->findOneBy(['id' => $pd[5]]));
            $producer->setCity($this->cityRepository->findOneBy(['id' => $pd[6]]));
            $producer->setPostalCode($pd[7]);
            $producer->setStreet($pd[8]);
            $producer->setEmail($pd[9]);
            $producer->setPhone($pd[10]);
            $producer->setUser($this->userRepository->findOneBy(['id' => $pd[11]]));
            $this->producerRepository->createEntity($producer);
        }
    }

    private function loadStores()
    {
        $storeData = [
            ['<h1>Welcome stranger</h1><h2>Take a look at my products!</h2>']
        ];

        foreach ($storeData as $sd) {
            $store = new Store();
            $store->setFrontPageHtml($sd[0]);
            $this->storeRepository->createEntity($store);
        }
    }

    private function loadOrders()
    {
        $orderData = [
            [5, 5, '441209205474295847260340']
        ];

        foreach ($orderData as $od) {
            $order = new Order();
            $order
                ->setUser($this->userRepository->findOneBy(['id' => $od[0]]))
                ->setUserCreated($this->userRepository->findOneBy(['id' => $od[1]]))
                ->setCode($od[2]);
            $this->orderRepository->createEntity($order);
        }
    }

    private function loadAddresses()
    {
        $addressData = [
            ['Home', 'producer', 9]
        ];

        foreach ($addressData as $ad) {
            $address = new Address();
            $address->setName($ad[0]);
            $address->setUser($this->userRepository->findOneBy(['login' => $ad[1]]));
            $address->setCountry($this->countryRepository->findOneBy(['id' => $ad[2]]));
            $this->addressRepository->createEntity($address);
        }
    }

    private function loadCategories()
    {
        $categoryData = [
            ['Shirts'],
            ['Trousers'],
            ['Shoes'],
            ['Hats'],
            ['Socks'],
            ['Sandals', 3],
            ['Trainers', 3],
            ['Flip Flops', 3],
            ['Chelsea Boots', 3],
        ];

        foreach ($categoryData as $cd) {
            $category = new Category();
            $category->setName($cd[0]);
            $category->setParent($this->categoryRepository->findOneBy(['id' => $cd[1] ?? null]));
            $this->categoryRepository->createEntity($category);
        }
    }

    private function loadProducts()
    {
        $productData = [
            ['Cool Shoe', 79.99, 3, 2],
            ['TF Hat No. 7', 12.5, 4, 3],
            ['Simple Top Hat', 65.99, 4, 2],
        ];

        foreach ($productData as $pd) {
            $product = new Product();
            $product->setName($pd[0]);
            $product->setPrice($pd[1]);
            $product->setCategory($this->categoryRepository->findOneBy(['id' => $pd[2]]));
            $product->setProducer($this->producerRepository->findOneBy(['id' => $pd[3]]));
            $this->productRepository->createEntity($product);
        }
    }

    private function loadParameters()
    {
        $parameterData = [
            ['Weight', 'g'],
            ['Material', ''],
        ];

        foreach ($parameterData as $pd) {
            $parameter = new Parameter();
            $parameter->setName($pd[0]);
            $parameter->setUnit($pd[1]);
            $this->parameterRepository->createEntity($parameter);
        }
    }

    private function loadProductParameters()
    {
        $productParameterData = [
            [1, 1, '250'],
            [1, 2, 'Wool'],
        ];

        foreach ($productParameterData as $ppd) {
            $productParameter = new ProductParameter();
            $productParameter->setProduct($this->productRepository->findOneBy(['id' => $ppd[0]]));
            $productParameter->setParameter($this->parameterRepository->findOneBy(['id' => $ppd[1]]));
            $productParameter->setValue($ppd[2]);
            $this->productParameterRepository->createEntity($productParameter);
        }
    }

    private function loadProductImages()
    {
        $productImageData = [
            ['uploads/images/shoes/cool-1.jpg', 1, 1],
            ['uploads/images/shoes/cool-2.jpg', 1, 2],
            ['uploads/images/hats/tf7.jpg', 2, 1],
        ];

        foreach ($productImageData as $pid) {
            $productImage = new ProductImage();
            $productImage->setFilePath($pid[0]);
            $productImage->setProduct($this->productRepository->findOneBy(['id' => $pid[1]]));
            $productImage->setSequencePosition($pid[2]);
            $this->productImageRepository->createEntity($productImage);
        }
    }

    private function loadVariants()
    {
        $variantData = [
            ['Black'],
            ['White'],
            ['Red'],
        ];

        foreach ($variantData as $vd) {
            $variant = new Variant();
            $variant->setName($vd[0]);
            $this->variantRepository->createEntity($variant);
        }
    }

    private function loadProductVariants()
    {
        $productVariantData = [
            [1, 1, 2],
            [1, 2, 3],
            [2, 1, 55],
            [2, 3, 9999]
        ];

        foreach ($productVariantData as $pvd) {
            $productVariant = new ProductVariant();
            $productVariant->setProduct($this->productRepository->findOneBy(['id' => $pvd[0]]))
                ->setVariant($this->variantRepository->findOneBy(['id' => $pvd[1]]))
                ->setAvailability($pvd[2]);
            $this->productVariantRepository->createEntity($productVariant);
        }
    }

    private function loadOrderStatuses()
    {
        $orderStatusData = [
            ['Queued'],
            ['Pending'],
            ['Completed'],
            ['Rejected'],
        ];

        foreach ($orderStatusData as $osd) {
            $orderStatus = new OrderStatus();

            $orderStatus->setName($osd[0]);

            $this->orderStatusRepository->createEntity($orderStatus);
        }
    }
}
