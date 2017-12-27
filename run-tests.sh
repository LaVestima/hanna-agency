#!/bin/bash

clear

testFiles=(
    src/LaVestima/HannaAgency/AccessControlBundle/Tests/Controller/ForgotPasswordControllerTest.php
    src/LaVestima/HannaAgency/AccessControlBundle/Tests/Controller/LoginControllerTest.php
    src/LaVestima/HannaAgency/AccessControlBundle/Tests/Controller/RegisterControllerTest.php
    src/LaVestima/HannaAgency/HomepageBundle/Tests/Controller/HomepageControllerTest.php
    src/LaVestima/HannaAgency/InfrastructureBundle/Tests/Controller/Helper/CrudHelperTest.php
    src/LaVestima/HannaAgency/OrderBundle/Tests/Controller/OrderControllerTest.php
    src/LaVestima/HannaAgency/UserManagementBundle/Tests/Controller/Crud/UserCrudControllerTest.php
    src/LaVestima/HannaAgency/UserManagementBundle/Tests/Controller/UserControllerTest.php
)

for i in "${testFiles[@]}"
do
    echo "################################################################################"
    echo -en "\e[1m"
    echo $i | rev | cut -d'/' -f 1 | rev
    echo -en "\e[0m"
    phpunit $i
done
