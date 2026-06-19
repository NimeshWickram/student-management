import api from '../../api/axios';

export const getStudents = async (page = 1, search = '') => {
  const response = await api.get(`/api/students?page=${page}&search=${search}`);
  return response.data;
};

export const createStudent = async (data) => {
  const response = await api.post('/api/students', data);
  return response.data;
};

export const updateStudent = async (id, data) => {
  const response = await api.put(`/api/students/${id}`, data);
  return response.data;
};

export const deleteStudent = async (id) => {
  const response = await api.delete(`/api/students/${id}`);
  return response.data;
};
