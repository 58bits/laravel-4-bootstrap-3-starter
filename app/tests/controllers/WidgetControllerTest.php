<?php

# /app/tests/controllers/WidgetControllerTest.php

class WidgetControllerTest extends TestCase
{
    public function __Construct()
    {
		// Nothing to do here.
    }

    public function setUp()
    {
        parent::setUp();
		$this->mock = \Mockery::mock('Eloquent','Widget');
        $this->app->instance('Widget', $this->mock);
    }

    public function tearDown()
	{
		parent::tearDown();
        \Mockery::close();
    }

    /**
     * Index
     */
	public function testIndex()
    {

		$this->client->request('GET', 'widgets');
        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('title');
    }

    /**
     * Show
     */
    public function testShow()
	{
		$this->mock
			->shouldReceive('find')
			->with(1)
			->once()
			->andReturn((object)array('id'=>1, 'name'=>'Widget-name','description'=>'Widget description'));

		$crawler = $this->client->request('GET', 'widgets/1');
        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('title');
		$this->assertCount(1, $crawler->filter('h3:contains("Widget Details")'));
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
			->shouldReceive('setAttribute')
			->times(3)
			->andSet('id', 1);

        $this->call('POST', 'widgets', array(
            'name' => 'Fu-Widget',
            'description' => 'Widget description'
            ));

        $this->assertRedirectedToRoute('widgets.create');
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
        $this->assertSessionHasErrors(['description']);
	}

}
