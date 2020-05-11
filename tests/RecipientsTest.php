<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use ReflectionObject;

class RecipientsTest extends TestCase
{
    public function testSettingSingleChannelStringRecipient()
    {
        $slack = \Slack::to('#general');

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['#general'], $recipientsArray);
    }

    public function testSettingSingleUserStringRecipient()
    {
        $slack = \Slack::to('@user');

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user'], $recipientsArray);
    }

    public function testSettingMultipleUsersStringRecipient()
    {
        $slack = \Slack::to('@user1', '@user2', '@user3');

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user1', '@user2', '@user3'], $recipientsArray);
    }

    public function testSettingSingleUserArrayRecipient()
    {
        $slack = \Slack::to(['@user']);

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user'], $recipientsArray);
    }

    public function testSettingMultipleUsersArrayRecipient()
    {
        $slack = \Slack::to(['@user1', '@user2', '@user3']);

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user1', '@user2', '@user3'], $recipientsArray);
    }

    public function testSettingSingleUserCollectionRecipient()
    {
        $slack = \Slack::to(collect(['@user']));

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user'], $recipientsArray);
    }

    public function testSettingMultipleUsersCollectionRecipient()
    {
        $slack = \Slack::to(collect(['@user1', '@user2', '@user3']));

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@user1', '@user2', '@user3'], $recipientsArray);
    }

    public function testSettingSingleUserObjectRecipient()
    {
        $slack = \Slack::to(new FakeUser());

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@fake'], $recipientsArray);
    }

    public function testSettingMultipleUsersObjectRecipient()
    {
        $slack = \Slack::to(collect([new FakeUser(), new AnotherFakeUser()]));

        $recipientsArray = $this->getPrivatePropertyValueFromObject('recipients', $slack);

        $this->assertEquals(['@fake', '@another_fake'], $recipientsArray);
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

class FakeUser extends Model
{
    public function getSlackChannelAttribute()
    {
        return '@fake';
    }
}

class AnotherFakeUser extends Model
{
    public function getSlackChannelAttribute()
    {
        return '@another_fake';
    }
}
