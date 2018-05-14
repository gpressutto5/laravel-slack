<?php

namespace Tests;

use ReflectionObject;
use Pressutto\LaravelSlack\Slack;

class RecipientsTest extends TestCase
{
    public function testSettingSingleChannelStringRecipient()
    {
        $slack = (new Slack())->to('#general');

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['#general'], $recipientsArray);
    }

    public function testSettingSingleUserStringRecipient()
    {
        $slack = (new Slack())->to('@user');

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user'], $recipientsArray);
    }

    public function testSettingMultipleUsersStringRecipient()
    {
        $slack = (new Slack())->to('@user1', '@user2', '@user3');

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user1', '@user2', '@user3'], $recipientsArray);
    }

    public function testSettingSingleUserArrayRecipient()
    {
        $slack = (new Slack())->to(['@user']);

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user'], $recipientsArray);
    }

    public function testSettingMultipleUsersArrayRecipient()
    {
        $slack = (new Slack())->to(['@user1', '@user2', '@user3']);

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user1', '@user2', '@user3'], $recipientsArray);
    }

    public function testSettingSingleUserCollectionRecipient()
    {
        $slack = (new Slack())->to(collect(['@user']));

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user'], $recipientsArray);
    }

    public function testSettingMultipleUsersCollectionRecipient()
    {
        $slack = (new Slack())->to(collect(['@user1', '@user2', '@user3']));

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user1', '@user2', '@user3'], $recipientsArray);
    }

    /**
     * @param string $propertyName
     * @param object $object
     *
     * @return mixed
     */
    private function getPrivatePropertyValueFromObject(string $propertyName, $object)
    {
        $reflection = new ReflectionObject($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
