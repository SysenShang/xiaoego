<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\caching;

/**
 * ExpressionDependency represents a dependency based on the result of a PHP expression.
 *
 * ExpressionDependency will use `eval()` to evaluate the PHP expression.
 * The dependency is reported as unchanged if and only if the result of the expression is
 * the same as the one evaluated when storing the data to cache.
 *
 * A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
 * please refer to the [php manual](http://www.php.net/manual/en/language.expressions.php).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ExpressionDependency extends Dependency
{
    /**
     * @var string the string representation of a PHP expression whose result is used to determine the dependency.
     * A PHP expression can be any PHP code that evaluates to a value. To learn more about what an expression is,
     * please refer to the [php manual](http://www.php.net/manual/en/language.expressions.php).
     */
    public $expression = 'true';

}