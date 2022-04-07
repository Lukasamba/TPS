using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using TPS.Data;
using TPS.Data.Model;
using TPS.Extensions;
using Microsoft.AspNetCore.Authorization;

namespace TPS.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class TeamMemberController : ControllerBase
    {
        private readonly TPSDataContext _context;

        public TeamMemberController(TPSDataContext context)
        {
            _context = context;
        }

        [HttpGet]
        [ProducesResponseType(StatusCodes.Status200OK)]
        public async Task<ActionResult<IEnumerable<TeamMember>>> GetTeamMembers()
        {
            return await _context.TeamMembers.ToListAsync();
        }

        [HttpPost]
        [ProducesResponseType(StatusCodes.Status201Created)]
        [ProducesResponseType(StatusCodes.Status400BadRequest)]
        [Authorize("admin")]
        public async Task<ActionResult<TeamMember>> CreateTeamMember([FromBody] TeamMember teamMember)
        {
            if (teamMember == null)
                return BadRequest("No data provided for object to be created.");
            if (teamMember.UserId != default)
                return BadRequest("Id has been set on create request, please do not do that, set id to 0 or ommit it.");

            _context.TeamMembers.Add(teamMember);
            await _context.SaveChangesAsync();

            return CreatedAtAction(nameof(GetTeamMember), new { id = teamMember.UserId }, teamMember);
        }

        [HttpGet("{id}")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status404NotFound)]
        public async Task<ActionResult<TeamMember>> GetTeamMember(int id)
        {
            var teamMember = await _context.TeamMembers.FindAsync(id);

            if (teamMember == null)
                return NotFound();

            return teamMember;
        }

        [HttpPatch("{id}")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status404NotFound)]
        [Authorize("admin")]
        public async Task<ActionResult<TeamMember>> UpdateTeamMember(int id, TeamMember teamMember)
        {
            var databasePublishedProblem = await _context.TeamMembers.FindAsync(id);
            if (databasePublishedProblem == default)
                return NotFound();

            var ePublishedProblem = _context.Attach(databasePublishedProblem);

            ePublishedProblem.MovePropertyDataWhiteList(teamMember, new string[]
            {
                nameof(databasePublishedProblem.Team),
                //nameof(databasePublishedProblem.ProblemEn),
                //nameof(databasePublishedProblem.Created),
                //nameof(databasePublishedProblem.IssueId),
                //nameof(databasePublishedProblem.SolutionId),
            });

            await _context.SaveChangesAsync();

            return Ok(ePublishedProblem.Entity);
        }

        
        [HttpDelete("{id}")]
        [ProducesResponseType(StatusCodes.Status204NoContent)]
        [ProducesResponseType(StatusCodes.Status404NotFound)]
        [Authorize("admin")]
        public async Task<IActionResult> DeleteTeamMember(int id)
        {
            var teamMember = await _context.TeamMembers.FindAsync(id);
            if (teamMember == null)
                return NotFound();

            _context.TeamMembers.Remove(teamMember);
            await _context.SaveChangesAsync();

            return NoContent();
        }
    }
}