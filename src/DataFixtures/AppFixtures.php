<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Conversation;
use App\Entity\Country;
use App\Entity\Message;
use App\Entity\Order;
use App\Entity\Parameter;
use App\Entity\ShipmentOption;
use App\Entity\Store;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductParameter;
use App\Entity\ProductVariant;
use App\Entity\Role;
use App\Entity\StoreSubuser;
use App\Entity\User;
use App\Entity\UserSetting;
use App\Entity\Variant;
use App\Repository\AddressRepository;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\ConversationRepository;
use App\Repository\CountryRepository;
use App\Repository\MessageRepository;
use App\Repository\OrderRepository;
use App\Repository\ParameterRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductParameterRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\RoleRepository;
use App\Repository\ShipmentOptionRepository;
use App\Repository\StoreRepository;
use App\Repository\StoreSubuserRepository;
use App\Repository\UserRepository;
use App\Repository\UserSettingRepository;
use App\Repository\VariantRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// COMMAND: sf doctrine:fixtures:load
class AppFixtures extends Fixture
{
    private $addressRepository;
    private $categoryRepository;
    private $cityRepository;
    private $conversationRepository;
    private $countryRepository;
    private $messageRepository;
    private $orderRepository;
    private $parameterRepository;
    private $productImageRepository;
    private $productParameterRepository;
    private $productRepository;
    private $productVariantRepository;
    private $roleRepository;
    private $shipmentOptionRepository;
    private $storeRepository;
    private $storeSubuserRepository;
    private $userRepository;
    private $userSettingRepository;
    private $variantRepository;

    public function __construct(
        AddressRepository $addressRepository,
        CategoryRepository $categoryRepository,
        CityRepository $cityRepository,
        ConversationRepository $conversationRepository,
        CountryRepository $countryRepository,
        MessageRepository $messageRepository,
        OrderRepository $orderRepository,
        ParameterRepository $parameterRepository,
        ProductImageRepository $productImageRepository,
        ProductParameterRepository $productParameterRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        RoleRepository $roleRepository,
        ShipmentOptionRepository $shipmentOptionRepository,
        StoreRepository $storeRepository,
        StoreSubuserRepository $storeSubuserRepository,
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository,
        VariantRepository $variantRepository
    ) {
        $this->addressRepository = $addressRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cityRepository = $cityRepository;
        $this->conversationRepository = $conversationRepository;
        $this->countryRepository = $countryRepository;
        $this->messageRepository = $messageRepository;
        $this->orderRepository = $orderRepository;
        $this->parameterRepository = $parameterRepository;
        $this->productImageRepository = $productImageRepository;
        $this->productParameterRepository = $productParameterRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->roleRepository = $roleRepository;
        $this->shipmentOptionRepository = $shipmentOptionRepository;
        $this->storeRepository = $storeRepository;
        $this->storeSubuserRepository = $storeSubuserRepository;
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

        $this->loadConversations();

        $this->loadMessages();

        $this->loadShipmentOptions();

        $this->loadStoreSubusers();
    }

    private function loadRoles(): void
    {
        $roleData = [
            ['Super Administrator', 'ROLE_SUPER_ADMIN'],
            ['Store Admin', 'ROLE_STORE_ADMIN'],
            ['User', 'ROLE_USER'],
            ['View dashboard', 'ROLE_VIEW_DASHBOARD', true],
            ['Read messages', 'ROLE_READ_MESSAGES', true],
            ['Write messages', 'ROLE_WRITE_MESSAGES', true],
            ['Add products', 'ROLE_ADD_PRODUCTS', true],
            ['Edit products', 'ROLE_EDIT_PRODUCTS', true],
            ['Delete products', 'ROLE_DELETE_PRODUCTS', true],
            ['View statistics', 'ROLE_VIEW_STATISTICS', true],
        ];

        foreach ($roleData as $rd) {
            $role = new Role();
            $role->setName($rd[0]);
            $role->setCode($rd[1]);
            $role->setSubrole($rd[2] ?? false);
            $this->roleRepository->createEntity($role);
        }
    }

    private function loadUsers(): void
    {
        $userData = [
            ['admin', 'admin@admin.admin', '$argon2i$v=19$m=1024,t=16,p=2$WkRnMTAwU25vWGRUaWJ3Yw$Wws1yFIRpI1UP3sAUnTiKWSWnO6GWwEktswJO0BeuQA', 1, ['ROLE_SUPER_ADMIN']],
            ['producer', 'prod@prod.prod', '$argon2i$v=19$m=1024,t=16,p=2$ZW5wem5oMFd1a2tYVlVxUg$NYvqhwv5s787f3yzAJ0BA7M+rHcQF+FrOFHloFTCG2U', 3, []],
            ['subuser', 'sub@sub.subuser', '$argon2i$v=19$m=1024,t=16,p=2$RzdhVzBVQnZITWxYbHpSWg$8af8WAAmVzRpaywpBfGJWnkSuu3wj9UrSQZjUHoLmgQ', 6],
            ['subuser2', 'sub2', '', 6],
            ['user', 'user@used.user', '$argon2i$v=19$m=1024,t=16,p=2$d2lGTmgxWTg3QVlJSTY4Zw$qGqrYwn8FexTo3Hu7prerL5Q1GgNUByXBADUBHpSQ0M', 5],
        ];

        foreach ($userData as $ud) {
            $user = new User();
            $user->setLogin($ud[0]);
            $user->setEmail($ud[1]);
            $user->setPasswordHash($ud[2]);
            $user->setRole($this->roleRepository->findOneBy(['id' => $ud[3]]));
            $user->setRoles($ud[4] ?? []);
            $this->userRepository->createEntity($user);
        }
    }

    private function loadUserSettings(): void
    {
        $userSettingData = [
            [1, 'de', 1],
            [2, 'pl', 0],
            [3, 'fr', 0],
            [4, 'en', 1],
        ];

        foreach ($userSettingData as $usd) {
            $userSetting = new UserSetting();
            $userSetting->setUser($this->userRepository->findOneBy(['id' => $usd[0]]))
                ->setLocale($usd[1])
                ->setNewsletter($usd[2]);
            $this->userSettingRepository->createEntity($userSetting);
        }
    }

    private function loadCountries(): void
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
            $country->setCode($cd[1]);
            $this->countryRepository->createEntity($country);
        }
    }

    private function loadCities(): void
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

    private function loadStores(): void
    {
        $storeData = [
            [
                'Opticomp', 'Opticomp Clothing', null, null,
                'US773365342', 5, 12, 'CA 92121', '331 Hamill Avenue', 'LarrySLowell@armyspy.com',
                '858-401-5106', 4, true
            ],
            [
                'Omni', 'Omni Architectural Designs', 'Christopher', 'Harris',
                'GB028338520', 9, 13, 'PH33 3AF', '50 Old Chapel Road', 'RileyCraig@teleworm.us',
                '078 2966 5839', 2, true
            ],
            [
                'Asiatic', 'Asiatic Solutions', null, null,
                'FR69210542996', 3, 14, '74100', '65, Avenue De Marlioz', 'BrigittePatry@teleworm.us',
                '04.29.54.23.82', 1, true
            ]
        ];

        foreach ($storeData as $sd) {
            $store = new Store();
            $store->setShortName($sd[0]);
            $store->setFullName($sd[1]);
            $store->setFirstName($sd[2]);
            $store->setLastName($sd[3]);
            $store->setVat($sd[4]);
            $store->setCountry($this->countryRepository->findOneBy(['id' => $sd[5]]));
            $store->setCity($this->cityRepository->findOneBy(['id' => $sd[6]]));
            $store->setPostalCode($sd[7]);
            $store->setStreet($sd[8]);
            $store->setEmail($sd[9]);
            $store->setPhone($sd[10]);
            $store->setAdmin($this->userRepository->findOneBy(['id' => $sd[11]]));
            $store->setActive($sd[12]);
            $this->storeRepository->createEntity($store);
        }
    }

    private function loadOrders(): void
    {
        $orderData = [
            [4, 4, '441209205474295847260340']
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

    private function loadAddresses(): void
    {
        $addressData = [
            ['Home', 'producer', 9, 'Examplary street 69', '99-999']
        ];

        foreach ($addressData as $ad) {
            $address = new Address();
            $address->setName($ad[0]);
            $address->setUser($this->userRepository->findOneBy(['login' => $ad[1]]));
            $address->setCountry($this->countryRepository->findOneBy(['id' => $ad[2]]));
            $address->setStreet($ad[3]);
            $address->setZipCode($ad[4]);
            $this->addressRepository->createEntity($address);
        }
    }

    private function loadCategories(): void
    {
        $categoryData = [
            ['Electronics'],
            ['Clothing'],
            ['Toys'],
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

    private function loadProducts(): void
    {
        $productData = [
            ['Cool Shoe', 79.99, 3, 2, 'Cool-Shoe-39', true, 'The greatest shoe in the world. Exclusive here!!!'],
            ['TF Hat No. 7', 12.5, 4, 3],
            ['Simple Top Hat', 65.99, 4, 2],
            ['11cm Baby Rubber Race Squeaky Duck Bath Toys for Children Fun Educational Musical Big Yellow Duck Bathroom Water Bathing Toys', 1.75, 3, 2, '11cm-Baby-Rubber-Race-Squeaky-Duck-Bath-Toys-for-Children-Fun-Educational-Musical-Big-Yellow-Duck-Bathroom-Water-Bathing-Toys', true],
        ];

        foreach ($productData as $pd) {
            $product = new Product();
            $product->setName($pd[0]);
            $product->setPrice($pd[1]);
            $product->setCategory($this->categoryRepository->findOneBy(['id' => $pd[2]]));
            $product->setStore($this->storeRepository->findOneBy(['id' => $pd[3]]));
            $product->setPathSlug($pd[4] ?? null);
            $product->setActive($pd[5] ?? false);
            $product->setDescription($pd[6] ?? null);
            $this->productRepository->createEntity($product);
        }
    }

    private function loadParameters(): void
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

    private function loadProductParameters(): void
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

    private function loadProductImages(): void
    {
        $productImageData = [
            ['uploads/images/shoes/cool-1.jpg', 1, 1],
            ['uploads/images/shoes/cool-2.jpg', 1, 2],
            ['uploads/images/hats/tf7.jpg', 2, 1],
            ['uploads/images/duck-1.jpg', 4, 1],
            ['uploads/images/duck-2.jpg', 4, 2],
            ['uploads/images/duck-3.jpg', 4, 3],
            ['uploads/images/duck-4.jpg', 4, 4],
        ];

        foreach ($productImageData as $pid) {
            $productImage = new ProductImage();
            $productImage->setFilePath($pid[0]);
            $productImage->setProduct($this->productRepository->findOneBy(['id' => $pid[1]]));
            $productImage->setSequencePosition($pid[2]);
            $this->productImageRepository->createEntity($productImage);
        }
    }

    private function loadVariants(): void
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

    private function loadProductVariants(): void
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

    private function loadConversations(): void
    {
        $conversationData = [
            [2, 4]
        ];

        foreach ($conversationData as $cd) {
            $conversation = new Conversation();
            $conversation->setUserFrom($this->userRepository->findOneBy(['id' => $cd[0]]));
            $conversation->setUserTo($this->userRepository->findOneBy(['id' => $cd[1]]));

            $this->conversationRepository->createEntity($conversation);
        }
    }

    private function loadMessages(): void
    {
        $messageData = [
            [1, '2019-07-02 12:32:22', 'Hello, I have a question', 1, true],
            [1, '2019-07-03 09:01:23', 'Hi', 0, false]
        ];

        foreach ($messageData as $md) {
            $message = new Message();
            $message->setConversation($this->conversationRepository->findOneBy(['id' => $md[0]]))
                ->setDateCreated(new DateTime($md[1]))
                ->setContent($md[2])
                ->setIsRead($md[3])
                ->setIsFromInitiator($md[4]);

            $this->messageRepository->createEntity($message);
        }
    }

    private function loadShipmentOptions()
    {
        $shipmentOptionsData = [
            ['DHL', 11.50],
            ['ADC', 7.90],
            ['Fedex', 5.00]
        ];

        foreach ($shipmentOptionsData as $sod) {
            $shipmentOption = new ShipmentOption();
            $shipmentOption->setName($sod[0])
                ->setCost($sod[1]);

            $this->shipmentOptionRepository->createEntity($shipmentOption);
        }
    }

    private function loadStoreSubusers()
    {
        $storeSubuserData = [
            [3, 2, '$argon2i$v=19$m=1024,t=2,p=2$LmlYYlc0VmdVRVNzU0E1UQ$AsZ1tkDPa/FOIbYZ8H2PEW4Naibeh9rEabUwGQQkseQ'], // pass: store
            [2, 2, '$argon2i$v=19$m=1024,t=2,p=2$LmlYYlc0VmdVRVNzU0E1UQ$AsZ1tkDPa/FOIbYZ8H2PEW4Naibeh9rEabUwGQQkseQ', ['ROLE_STORE_ADMIN']], // pass: store
        ];

        foreach ($storeSubuserData as $ssd) {
            $storeSubuser = new StoreSubuser();
            $storeSubuser->setUser($this->userRepository->findOneBy(['id' => $ssd[0]]))
                ->setStore($this->storeRepository->findOneBy(['id' => $ssd[1]]))
                ->setPasswordHash($ssd[2])
                ->setRoles($ssd[3]);

            $this->storeSubuserRepository->createEntity($storeSubuser);
        }
    }
}
