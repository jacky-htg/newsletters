<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Route
{
    public static function start()
    {
        try {
            $controller_name = Core_Array::getGet('t', 'template');
            core::addSetting( array('controller'=> $controller_name ) );
			
			$action = Core_Array::getGet('a', 'index');
            $model_name = 'Model_' . $controller_name;
            $controller_name = 'Controller_' . $controller_name;
            $action_name = 'action_' . $action;
            
            core::requireEx('models', strtolower($model_name) . '.php');
            
            if (! core::requireEx('controllers', strtolower($controller_name) . '.php'))
				throw new Exception404('Can not open the file ' . core::pathTo('controllers', strtolower($controller_name) . '.php').'!');
            if (class_exists($controller_name)) {

                $controller = core::factory($controller_name);
                if (method_exists($controller, $action_name)) {
                    $controller->$action_name();
                } else {
					throw new Exception404('Method ' . $action_name . ' does not exist!');
                }
            } else {
				throw new Exception404('Class ' . $controller_name . ' does not exist!');
            }
        } catch (ExceptionSQL $exc) {
            if (DEBUG == 1) {
                echo "<!DOCTYPE html>";
                echo "<html>";
                echo "<head>";
                echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
                echo "<title>SQL error</title>";
                echo "</head>";
                echo "<body>";
                echo "<p>An error occurred while accessing SQL database!</p>";
                echo "<p>" . $exc->getSQLError() . "<br>" . nl2br($exc->getSQLQuery()) . "</p>";
                echo "<p>Error in file " . $exc->getFile() . " at line " . $exc->getLine() . "</p>";
                echo "</body>";
                echo "</html>";
				exit;
            } else {
                self::ErrorPage500();
            }
        } catch (Exception403 $exc) {
			if (DEBUG == 1) {
				echo "<!DOCTYPE html>";
				echo "<html>";
				echo "<head>";
				echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
				echo "<title>403 Forbidden</title>";
				echo "</head>";
				echo "<body>";
				echo "<p>" . $exc->getMessage() . "</p>";
				echo "</body>";
				echo "</html>";
				exit;
			} else {
				self::ErrorPage403();
			}			
        } catch (Exception404 $exc) {
			if (DEBUG == 1) {
				echo "<!DOCTYPE html>";
				echo "<html>";
				echo "<head>";
				echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
				echo "<title>404 Not Found</title>";
				echo "</head>";
				echo "<body>";
				echo "<p>" . $exc->getMessage() . "</p>";
				echo "</body>";
				echo "</html>";
				exit;
			} else {
				self::ErrorPage404();
			}
		}
		catch (Exception $exc) {
			if (DEBUG == 1) {
				echo "<!DOCTYPE html>";
				echo "<html>";
				echo "<head>";
				echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
				echo "<title>500 Internal Server Error</title>";
				echo "</head>";
				echo "<body>";
				echo "<p>" . $exc->getMessage() . "</p>";
				echo "<p>Error in file " . $exc->getFile() . " at line " . $exc->getLine() . "</p>";
				echo "</body>";
				echo "</html>";
				exit;
			} else {
				self::ErrorPage500();
			}			
		} 		
    }

	public static function ErrorPage403()
	{
		header('HTTP/1.1 403 Forbidden');
		header("Status: 403 Forbidden");
		header('Location: ./?t=page403');
		exit();
	}

	public static function ErrorPage404()
	{
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location: ./?t=page404');
		exit();
    }
	
	public static function ErrorPage500()
	{
        header('HTTP/1.1 500 Internal Server Error');
		header("Status: 500 Internal Server Error");
		header('Location: ./?t=page500');
		exit();
    }
}