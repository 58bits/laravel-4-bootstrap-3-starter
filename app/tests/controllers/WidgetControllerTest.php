<?php
 
# /app/tests/controllers/WidgetControllerTest.php
 
class WidgetControllerTest extends TestCase {
 
  public function testIndex()
  {
      $this->call('GET', 'widgets');
  }
 
}
