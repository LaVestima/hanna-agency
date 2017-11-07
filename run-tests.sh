#!/bin/bash

clear

testFiles=(
    src/LaVestima/HannaAgency/AccessControlBundle/Tests/Controller/LoginControllerTest.php
)

for i in "${testFiles[@]}"
do
    echo $i
done

#echo 'someletters_12345_moreleters.ext' | cut -d'_' -f 2
#echo 'src/LaVestima/HannaAgency/AccessControlBundle/Tests/Controller/LoginControllerTest.php' | cut -d'/' -f 4

echo -e "\e[1mAccess Control\e[0m"
#phpunit src/LaVestima/HannaAgency/AccessControlBundle/Tests/Controller/LoginControllerTest.php | awk '{if(NR==3)print}'
#phpunit src/LaVestima/HannaAgency/AccessControlBundle/Tests/Controller/RegisterControllerTest.php | awk '{if(NR==3)print}'

echo -e "\e[1mHomepage\e[0m"
phpunit src/LaVestima/HannaAgency/HomepageBundle/Tests/Controller/HomepageControllerTest.php | awk '{if(NR==3)print}'

echo -e "\e[1mInfrastructure\e[0m"
phpunit src/LaVestima/HannaAgency/InfrastructureBundle/Tests/Controller/Helper/CrudHelperTest.php | awk '{if(NR==3)print}'

echo "-----------------------------------------------"
echo -e "\e[1mOrder\e[0m"
phpunit src/LaVestima/HannaAgency/OrderBundle/Tests/Controller/OrderControllerTest.php

echo "-----------------------------------------------"
echo -e "\e[1mUser Management\e[0m"
phpunit src/LaVestima/HannaAgency/UserManagementBundle/Tests/Controller/Crud/UserCrudControllerTest.php
