<?php
/**
 * Created by 深圳市阿翼互联网有限公司.
 * User: yinliangliang
 * Date: 2019/1/13
 * Time: 14:25
 * file: ProjectModel.php
 * email:yll1024335892@163.com
 */

namespace app\model;

use think\Cache;
use think\Model;

class ProjectModel extends Model
{
    protected $name = 'project';
    protected $pk = "project_id";

    /**
     * 获取项目的文档树
     * @param int $project_id
     * @return array
     */
    public static function getProjectArrayTree($project_id)
    {
        if (empty($project_id)) {
            return [];
        }
        $tree = DocumentModel::where('project_id', '=', $project_id)
            ->order('doc_sort', 'ASC')
            ->field(['doc_id', 'doc_name', 'parent_id'])
            ->select();
        $jsonArray = [];

        if (empty($tree) === false) {
            foreach ($tree as &$item) {
                $tmp['id'] = $item->doc_id . '';
                $tmp['text'] = $item->doc_name;
                $tmp['parent'] = ($item->parent_id == 0 ? '#' : $item->parent_id) . '';

                $jsonArray[] = $tmp;
            }
        }
        return $jsonArray;
    }

    /**从缓存中获取项目信息
     * @param $project_id
     * @param bool $update
     * @return bool
     */
    public static function getProjectFromCache($project_id, $update = false)
    {
        if (empty($project_id)) {
            return false;
        }
        $key = 'project.id.' . $project_id;

        $project = $update or Cache::get($key);

        if (empty($project)) {
            $project = ProjectModel::find($project_id);
            if (empty($project)) {
                return false;
            }
            Cache::set($key, $project, 3600);
        }
        return $project;
    }

    /**
     * 获取项目的文档树Html结构
     * @param int $project_id
     * @param int $selected_id
     * @return string
     */
    public static function getProjectHtmlTree($project_id, $selected_id = 0)
    {
        if (empty($project_id)) {
            return '';
        }
        $tree = DocumentModel::where('project_id', '=', $project_id)
            ->field('doc_id,doc_name,parent_id')
            ->order('doc_sort', 'ASC')
            ->select();
        if (empty($tree) === false) {
            $parent_id = self::getSelecdNode($tree, $selected_id);

            return self::createTree(0, $tree, $selected_id, $parent_id);
        }
        return '';
    }


    protected static function getSelecdNode($array, $parent_id)
    {
        foreach ($array as $item) {
            if ($item['doc_id'] == $parent_id and $item['parent_id'] == 0) {
                return $item['doc_id'];
            } elseif ($item['doc_id'] == $parent_id and $item['parent_id'] != 0) {
                return self::getSelecdNode($array, $item['parent_id']);
            }
        }
        return 0;
    }

    protected static function createTree($parent_id, array $array, $selected_id = 0, $selected_parent_id = 0)
    {
        global $menu;

        $menu .= '<ul>';

        foreach ($array as $item) {
            if ($item['parent_id'] == $parent_id) {
                $selected = $item['doc_id'] == $selected_id ? ' class="jstree-clicked"' : '';
                $selected_li = $item['doc_id'] == $selected_parent_id ? ' class="jstree-open"' : '';

                $menu .= '<li id="' . $item['doc_id'] . '"' . $selected_li . '><a href="/docs/show/' . $item['doc_id'] . '" title="' . htmlspecialchars($item['doc_name']) . '"' . $selected . '>' . $item['doc_name'] . '</a>';

                $key = array_search($item['doc_id'], array_column($array, 'parent_id'));

                if ($key !== false) {
                    self::createTree($item['doc_id'], $array, $selected_id, $selected_parent_id);
                }
                $menu .= '</li>';
            }
        }
        $menu .= '</ul>';
        return $menu;
    }


    /**
     * 添加文档
     * @param $param
     */
    public function addProject($param)
    {
        try {
//            $result = $this->validate('ArticleValidate')->save($param);
            if (isset($param['project_id'])) {
                $id = $param['project_id'];
                unset($param['project_id']);
                $result = $this->save($param, ['project_id' => $id]);
            } else {
                $result = $this->save($param);
            }
            if (false === $result) {
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            } else {
                return msg(1, url('Document/doclist'), '添加文档成功');
            }
        } catch (\Exception $e) {
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 获取所有的文档的列表
     * @param $where
     * @return int|string
     */
    public function getAllProjects($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 获取文档的所有数据
     * @param $where
     * @param $offset
     * @param $limit
     * @return
     */
    public function getProjectsByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('project_id desc')->select();
    }

    /**
     * 更新文档的节点树
     * @param $data
     * @param $project_id
     */
    public function upDateTree($data,$project_id){
        $this->save(["doc_tree"=>$data],["project_id"=>$project_id]);
    }

    
}