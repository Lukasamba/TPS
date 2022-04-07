using TPS.Data.Model;
using TPS.Extensions;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace TPS.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class TeamController : ControllerBase
    {
        private readonly Data.TPSDataContext dataContext;
        private readonly IAuthorizationService _authorizationService;

        public TeamController(Data.TPSDataContext dataContext, IAuthorizationService authorizationService)
        {
            this.dataContext = dataContext;
            _authorizationService = authorizationService;
        }


        [HttpGet]
        [Authorize]
        public async Task<ActionResult<IEnumerable<Team>>> GetTeams()
        {
            return await dataContext.Teams.ToListAsync();

        }

        [HttpPost]
        [ProducesResponseType(StatusCodes.Status201Created)]
        [ProducesResponseType(StatusCodes.Status400BadRequest)]
        [Authorize]
        public async Task<ActionResult<Team>> CreateTeamAsync([FromBody] Team teamToCreate)
        {
            if (teamToCreate == null)
                return BadRequest("No data provided for object to be created.");
            if (teamToCreate.Id != default)
                return BadRequest("Id has been set on create request, please do not do that, set id to 0 or ommit it.");
            if (teamToCreate.Name == null)
                return BadRequest("No Name has been specified");

            var createdValue = await dataContext.AddAsync(teamToCreate);
            await dataContext.SaveChangesAsync();

            return CreatedAtAction(nameof(GetTeam), new { Id = createdValue.Entity.Id }, createdValue.Entity);
        }

        [HttpGet("{id}")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status404NotFound)]
        [Authorize]
        public async Task<ActionResult<Team>> GetTeam(int id)
        {
            var team = dataContext.Teams.AsQueryable().Where(i => i.Id.CompareTo(id) == 0).FirstOrDefault();
            if(team == default)
                return NotFound();
            
            return Ok(team);
        }

        [HttpPatch("{id}")]
        public async Task<ActionResult<Team>> UpdateTeamAsync(int id, [FromBody] Team team)
        {
            var databaseIssue = dataContext.Teams.AsQueryable().Where(i => i.Id.CompareTo(id) == 0).FirstOrDefault();
            if (databaseIssue == default)
                return NotFound();
            var eIssue = dataContext.Attach(databaseIssue);
            eIssue.MovePropertyDataWhiteList(team, new string[] {
                nameof(databaseIssue.Description),
                nameof(databaseIssue.Name)
            });
            await dataContext.SaveChangesAsync();
            return Ok(eIssue.Entity);
        }

        [HttpDelete("{id}")]
        [ProducesResponseType(StatusCodes.Status204NoContent)]
        [ProducesResponseType(StatusCodes.Status404NotFound)]
        [Authorize("admin")]
        public async Task<IActionResult> DeleteTeamAsync(int id)
        {
            var team = dataContext.Teams.AsQueryable().Where(i => i.Id.CompareTo(id) == 0).FirstOrDefault();
            if (team == default)
                return NotFound();
            dataContext.Teams.Remove(team);
            await dataContext.SaveChangesAsync();
            return NoContent();
        }
    }
}
