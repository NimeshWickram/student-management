import api from '../../api/axios';

export const getTeachers = async (page = 1, search = '') => {
  const response = await api.get(`/api/teachers?page=${page}&search=${search}`);
  return response.data;
};

export const createTeacher = async (data) => {
  const response = await api.post('/api/teachers', data);
  return response.data;
};

export const updateTeacher = async (id, data) => {
  const response = await api.put(`/api/teachers/${id}`, data);
  return response.data;
};

export const deleteTeacher = async (id) => {
  const response = await api.delete(`/api/teachers/${id}`);
  return response.data;
};
