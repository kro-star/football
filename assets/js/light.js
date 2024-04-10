let teams = document.querySelectorAll('.team');
teams.forEach(team => { team.addEventListener('click', (e) =>{
    const teamName = e.target.dataset.name;
    const sameTeam1 = document.querySelectorAll(`.match[data-name-team1="${teamName}"]`);
    const sameTeam2 = document.querySelectorAll(`.match[data-name-team2="${teamName}"]`);
  
    sameTeam1.forEach(element => {
      element.classList.toggle('light')});
    
    sameTeam2.forEach(element => {
      element.classList.toggle('light')});
    });
})