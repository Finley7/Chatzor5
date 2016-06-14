<?php
namespace App\Model\Table;

use App\Model\Entity\Mailkey;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mailkeys Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class MailkeysTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('mailkeys');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp', ['events' => [
            'Model.beforeSave' => [
                'created' => 'new'
            ]
        ]]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('token', 'create')
            ->notEmpty('token')
            ->add('token', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->integer('activated')
            ->requirePresence('activated', 'create')
            ->notEmpty('activated');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['token']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}
