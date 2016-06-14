<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Roles
 */
class UsersTable extends Table
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

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp',
            ['events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                ]
            ]]
        );

        $this->hasMany('Mailkeys', [
            'foreignKey' => 'user_id',
        ]);

        $this->belongsToMany('Roles', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'role_id',
            'joinTable' => 'roles_users'
        ]);

        $this->hasOne('PrimaryRole', [
            'className' => 'Roles',
            'foreignKey' => 'id',
            'bindingKey' => 'primary_role',
            'propertyName' => 'primary_role'
        ]);

        $this->hasOne('Activities', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
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
        return $validator
            ->notEmpty('username', __('An username is required!'))
            ->notEmpty('password', __('A password is required'))
            ->add('password_verify', 'matches', [
                'rule' => ['compareWith', 'password'],
                'message' => __('The passwords do not match!')
            ])
            ->email('email', __('This is not a valid e-mail!'))
            ->notEmpty('email', __('An e-mail is required!'));
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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
