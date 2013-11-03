<?php

# /app/tests/controllers/WidgetControllerTest.php

class WidgetControllerTest extends TestCase
{
    public function __Construct()
    {
        $this->mock = Mockery::mock('Eloquent', 'Widget');
    }

    public function setUp()
    {
        parent::setUp();

        $this->app->instance('Widget', $this->mock);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Index
     */
    public function testIndex()
    {
        $this->call('GET', 'widgets');

        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('title');
    }

    /**
     * Show
     */
    public function testShow()
    {
        //$this->mock->shouldReceive('find')->with(1)->once()->andReturn(array('id'=>1));

        $this->mock
        ->shouldReceive('find')
        ->once()
        ->andSet('id', 1);

        //$this->call('GET', 'widgets/1');
        $this->action('GET', 'WidgetController@show', array(
            'widgets' => 1
            ));

        $this->assertTrue($this->client->getResponse()->isOk());
    }

    /**
     * Create
     */
    public function testCreate()
    {
        $crawler = $this->client->request('GET', 'widgets/create');

        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('title');
        $this->assertCount(1, $crawler->filter('h3:contains("Create a New Widget")'));

    }

    /**
     * Store Success
     */
    public function testStoreSuccess()
    {
        $this->mock
        ->shouldReceive('save')
        ->once()
        ->andSet('id', 1);

        $this->call('POST', 'widgets', array(
            'name' => 'Fu-Widget',
            'description' => 'Widget description'
            ));

        $this->assertRedirectedToRoute('widgets.index');
    }

    /**
     * Store Fail
     */
    public function testStoreFail()
    {

        $this->call('POST', 'widgets', array(
            'name' => '',
            'description' => ''
            ));

        $this->assertRedirectedToRoute('widgets.create');
        $this->assertSessionHasErrors(['name']);

    }
}
