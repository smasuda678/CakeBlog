<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use RuntimeException;

/**
 * Manage Controller
 *
 * @property \App\Model\Table\ArticlesTable $Manage
 *
 * @method \App\Model\Entity\Articles[] paginate($object = null, array $settings = [])
 */

class ManageController extends AppController
{

    public function initialize()
   {
       parent::initialize();
       $this->Articles = TableRegistry::get('Articles');
       $this->Pictures = TableRegistry::get('Pictures');
   }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Pictures']
        ];

        $articles = $this->paginate($this->Articles);

        $this->set(compact('articles'));
        $this->set('_serialize', ['articles']);

        $this->set('pagename', '管理画面');
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id === null) {
            $article = $this->Articles->newEntity();
        } else {
            $article = $this->Articles->get($id, [
                'contain' => ['Pictures']
            ]);
        }

        $picture = $this->Pictures->newEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->getData(), ['associated' => ['Pictures']]);

            $tmp = $this->request->data['picture_data']['tmp_name'];
            if(is_uploaded_file($tmp)) {
                $filename = date('Y_m_d_H_i_s').".jpg";
                $dir = "/xampp/htdocs/CakeBlog/blog/webroot/uploads/pictures";
                move_uploaded_file($tmp, $dir . DS . $filename);

                $picture = $this->Pictures->patchEntity($picture, ['data' => $filename]);
                if ($this->Pictures->save($picture)) {
                    echo "saved\n";
                }
                else {
                    $this->Flash->error(__('The picture could not be saved.'));
                }
            }
            $article['picture_id'] = $picture['id'];
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }

        $this->set(compact('article'));
        $this->set('_serialize', ['article']);
        $this->set('pagename', '記事追加/編集');//ページタイトル
        $this->set('id', $id);//delete ボタン判断用

    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article has been deleted.'));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
