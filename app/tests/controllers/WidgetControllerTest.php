<?php
 
# /app/tests/controllers/WidgetControllerTest.php
 
class WidgetControllerTest extends TestCase {
 
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

  public function testIndex()
  {
      $this->call('GET', 'widgets');

      $this->assertViewHas('title');
  }

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
