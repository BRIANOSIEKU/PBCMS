class LecturersController < ApplicationController
  before_action :authenticate_user!
  before_action :ensure_academic_dean
  before_action :set_lecturer, only: [ :show, :edit, :update, :destroy ]

  # GET /lecturers
  def index
    @lecturers = Lecturer.all.order(:full_name)
  end

  # GET /lecturers/:id
  def show
  end

  # GET /lecturers/new
  def new
    @lecturer = Lecturer.new
  end

  # POST /lecturers
  def create
    @lecturer = Lecturer.new(lecturer_params)
    if @lecturer.save
      redirect_to @lecturer, notice: "Lecturer was successfully created."
    else
      render :new
    end
  end

  # GET /lecturers/:id/edit
  def edit
  end

  # PATCH/PUT /lecturers/:id
  def update
    if @lecturer.update(lecturer_params)
      redirect_to @lecturer, notice: "Lecturer was successfully updated."
    else
      render :edit
    end
  end

  # DELETE /lecturers/:id
  def destroy
    @lecturer.destroy
    redirect_to lecturers_path, notice: "Lecturer was successfully deleted."
  end

  private

  def set_lecturer
    @lecturer = Lecturer.find(params[:id])
  end

  def lecturer_params
    params.require(:lecturer).permit(
      :full_name, :id_number, :email, :phone, :gender,
      :qualification, :department, :passport_photo
    )
  end

  def ensure_academic_dean
    redirect_to root_path, alert: "Access denied." unless current_user.role == "academic_dean"
  end
end
