<?php
/**
 * @author : goodtimp
 * @time : 2018-3-2
 */

namespace App\Domain\Question;

use App\Model\Question\Search as ModelSearchQ;
use App\Model\Category as ModelCategory;
use App\Model\KeyWord as ModelKeyWords;
use PhalApi\Exception;

class QTools
{
  /**合并Question中keywords与Keysweight字段，生成相应数组 
   * @param $keywors Quesiont表中KeyWords字段值
   * @param $keysweight Quesiont表中KeysWeight字段值
  */
  public static function mergeQuestionKeys($keywords,$keysweight)
  {
    try{
    
      $id = explode(",", $keywords); //转数组
      $weight =explode(",", $keysweight);
      
      $reslut=array();
      for($i=0;$i<count($id);$i++)
      {
        $reslut[$i]["Id"]=$id[$i];
        $reslut[$i]["Weight"]=$weight[$i];
      }
    }
    catch (Exception $e)
    {
      return [];
    }
    return $reslut;
  }

  /**剔除用户已经操作过的题目 */
  public static function deleteQuestionsForUser($uid,$qs=null)
  {
  
    $mquestion = new ModelSearchQ();
    if($qs==null) $qs=$mquestion->getAllQuestion();


    $questions=$mquestion->mGetNotUserCollect($uid,$qs);
    return $questions;
  }
  /**获取可显示的题目信息(将categoryId换成name等) */
  public static function getQuestionViewById($id)
  {
    $mq=new ModelSearchQ();
    $mc=new ModelCategory();
    $mk=new ModelKeyWords();

    $q=$mq->getQuestionById($id);
    $q["Category"]=$mc->getCategoryById($q["CategoryId"]);

    $q["Words"]=$mk->gesKeyWordsByIds($q["KeyWords"]);
    return $q;
  }
}
