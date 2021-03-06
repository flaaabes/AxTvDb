<?php

namespace AxTvDbTest\Client;

use AxTvDb\Client\Client as TvDbClient;
use AxTvDb\Client\Client;
use PHPUnit_Framework_TestCase;

/**
 * Test case for class AxTvDb\Client\Client
 *
 * @category AxTvDbTest
 * @package  AxTvDbTest\Client
 * @author   Michel Maas <michel@michelmaas.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link     https://github.com/AxaliaN/AxTvDb
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    /** @var Client */
    protected $client;

    public function setUp()
    {
        if(APIKEY == ""){
            $this->markTestIncomplete('Api key was not defined.');
        }

        $config = array(
            'client' => array(
                'baseUrl' => 'http://thetvdb.com',
                'apiKey' => APIKEY
            )
        );

        $this->client = new TvDbClient($config);
    }

    /**
     * Tests if the client gets construced properly
     */
    public function testIfClientCanBeConstructed()
    {
        $this->assertEquals('http://thetvdb.com',$this->client->getBaseUrl());
        $this->assertEquals(APIKEY,$this->client->getApiKey());
    }

    /**
     * Tests if the client can get a language from the TVDb
     */
    public function testIfLanguagesCanBeRetrieved()
    {
        $language = $this->client->getLanguage('en');

        $this->assertEquals(array('name'=>'English', 'abbreviation' => 'en', 'id' => 7), $language);
    }

    /**
     * @expectedException \Exception
     */
    public function testIfExceptionThrownOnInvalidLanguage()
    {
        $this->client->getLanguage('zz');
    }

    public function testIfServerTimeCanBeRetrieved()
    {
        $time = $this->client->getServerTime();

        $this->assertNotEquals(0, $time);
    }

    /**
     * Unfortunately, I can think of no easy way to test this better,
     * since the data retrieved can change anytime
     */
    public function testIfSeriesRetrieved()
    {
        $seriesData = $this->client->getSeriesByName('Vikings');

        $this->assertEquals('array', gettype($seriesData));
    }

    /**
     * Unfortunately, I can think of no easy way to test this better,
     * since the data retrieved can change anytime
     */
    public function testIfSerieDataRetrieved()
    {
        $seriesData = $this->client->getSerieById(75897);

        $this->assertEquals('object', gettype($seriesData));
    }

    public function testIfBannerXmlDownloaded()
    {
        $bannerData = $this->client->getBannersBySeriesId(75897);

        $this->assertEquals('array', gettype($bannerData));
    }

    public function testIfSerieEpisodesCanBeRetrieved()
    {
        $episodeData = $this->client->getEpisodesBySerieId(75897);

        $this->assertEquals('array', gettype($episodeData));
    }

    /**
     * @expectedException \Exception
     */
    public function testIfExceptionThrownSerieEpisodesCanBeRetrievedInZip()
    {
        $episodeData = $this->client->getEpisodesBySerieId(75897, Client::DEFAULT_LANGUAGE, Client::FORMAT_ZIP);

        $this->assertEquals('array', gettype($episodeData));
    }

    public function testIfEpisodeReturned()
    {
        $episodeData = $this->client->getEpisode(75897, 1, 1);

        $this->assertEquals('AxTvDb\Episode\Episode', get_class($episodeData));
    }

    public function testIfEpisodeReturnedById()
    {
        $episodeData = $this->client->getEpisodeById(179540);

        $this->assertEquals('AxTvDb\Episode\Episode', get_class($episodeData));
    }

    public function testIfUpdatesCanBeRetrieved()
    {
        $updatesData = $this->client->getUpdates(strtotime("-1 day"));
        $this->assertEquals('array', gettype($updatesData));
    }

}