<?php
declare(strict_types=1);

$firstArray = [
    'myName' => 'Francis',
    'myAge' => 35,
    'myMoneyInPocket' =>  512.45,
    'happy' => true,
    'myFriends' => [
        'Angel',
        'Mr. X',
    ],
];

$myModel = new BasicModel($firstArray);

echo 'My Name is :' . $myModel->getMyName();
echo 'I am ' . $myModel->getMyAge() . ' years old';
echo 'I have ' . $myModel->getMyMoneyInPocket() . '$ in my pocket';
echo 'My Friends are: ';
foreach ($myModel->getMyFriends() as $friend) {
    echo $friend;
}
echo $myModel->isHappy() ? 'I am happy': 'I am not happy';
