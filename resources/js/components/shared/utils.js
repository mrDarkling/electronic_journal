export default {
  redirect: function(to) {
    switch(to){
      case 'home':
        window.location.href = '/home';
        break;
      case 'students':
        window.location.href = '/students';
        break;
      case 'students-groups':
        window.location.href = '/students-groups';
        break;
      case 'users':
        window.location.href = '/users';
        break;
      case 'study-weeks':
        window.location.href = '/study-weeks';
        break;
      case 'study-subjects':
        window.location.href = '/study-subjects';
        break;
    }
  }
}
