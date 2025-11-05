class Lecturer < ApplicationRecord
  has_one_attached :passport_photo

  validates :full_name, :id_number, :email, :phone, :gender, :qualification, :department, presence: true
  validates :email, uniqueness: true, format: { with: URI::MailTo::EMAIL_REGEXP }
  validates :id_number, uniqueness: true
  validates :phone, format: { with: /\A\d{10,15}\z/, message: "must be a valid phone number" }
end
