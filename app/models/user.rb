class User < ApplicationRecord
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable, :trackable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :validatable

  enum :role, {
    principal: 0,
    academic_dean: 1,
    accountant: 2,
    lecturer: 3,
    student: 4
  }
end
