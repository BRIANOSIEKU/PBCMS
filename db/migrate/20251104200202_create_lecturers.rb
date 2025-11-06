class CreateLecturers < ActiveRecord::Migration[8.0]
  def change
    create_table :lecturers do |t|
      t.string :full_name
      t.string :id_number
      t.string :email
      t.string :phone
      t.string :gender
      t.string :qualification
      t.string :department

      t.timestamps
    end
  end
end
